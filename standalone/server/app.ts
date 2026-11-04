// SPDX-License-Identifier: GPL-2.0-or-later
import express from 'express';
import { createHash, randomBytes, timingSafeEqual } from 'node:crypto';
import { z } from 'zod';
import type { Database } from './db';

type Options = { password: string; demo?: boolean; secureCookies?: boolean; today?: () => string };
const sha = (s: string) => createHash('sha256').update(s).digest('hex');
const equal = (a: string, b: string) => timingSafeEqual(Buffer.from(sha(a)), Buffer.from(sha(b)));
const roomSchema = z.object({ roomNumber: z.string().trim().min(1).max(40), roomType: z.string().trim().min(1).max(80), capacity: z.number().int().min(1).max(20), rate: z.string().regex(/^(0|[1-9]\d{0,6})(\.\d{1,2})?$/) });
const bookingSchema = z.object({ roomId: z.number().int().positive(), guestName: z.string().trim().min(1).max(120), email: z.union([z.literal(''), z.string().email().max(190)]), phone: z.string().trim().max(40), guests: z.number().int().min(1).max(20), checkIn: z.string(), checkOut: z.string() });
function pesewas(s: string): number { const [whole, fraction = ''] = s.split('.'); return Number(whole) * 100 + Number(fraction.padEnd(2, '0')); }
function dateNumber(s: string): number | null { if (!/^\d{4}-\d{2}-\d{2}$/.test(s)) return null; const d = new Date(`${s}T00:00:00Z`); return Number.isFinite(d.getTime()) && d.toISOString().slice(0, 10) === s ? d.getTime() : null; }
function cookie(req: express.Request, name: string) { return req.headers.cookie?.split(';').map(x => x.trim()).find(x => x.startsWith(name + '='))?.slice(name.length + 1) || ''; }
class Problem extends Error { constructor(public status: number, message: string) { super(message); } }

export function createApp(db: Database, options: Options) {
  if (options.password.length < 12) throw new Error('Set an administrator password of at least 12 characters.');
  const app = express();
  app.disable('x-powered-by');
  app.use(express.json({ limit: '16kb' }));
  // SameSite cookies plus a non-simple request header prevent cross-origin form submissions.
  app.use('/api', (req, res, next) => {
    res.set('Cache-Control', 'no-store');
    if (!['GET', 'HEAD'].includes(req.method) && req.get('x-bookora-request') !== '1') return res.status(403).json({ error: 'Invalid request.' });
    next();
  });
  // Basic in-process login throttle is a prototype safeguard, not a distributed rate limiter.
  const attempts = new Map<string, { count: number; until: number }>();
  app.post('/api/login', async (req, res) => {
    const key = req.ip || 'unknown', now = Date.now();
    const current = attempts.get(key);
    if (current && current.until > now && current.count >= 10) return res.status(429).json({ error: 'Too many attempts. Try again later.' });
    if (req.body?.username !== 'admin' || typeof req.body?.password !== 'string' || !equal(req.body.password, options.password)) {
      const entry = current && current.until > now ? current : { count: 0, until: now + 15 * 60_000 };
      entry.count++; attempts.set(key, entry);
      return res.status(401).json({ error: 'Invalid username or password.' });
    }
    attempts.delete(key);
    const token = randomBytes(32).toString('hex');
    await db.query("INSERT INTO sessions (token_hash, expires_at) VALUES ($1, now() + interval '12 hours')", [sha(token)]);
    res.cookie('bookora_session', token, { httpOnly: true, sameSite: 'strict', secure: !!options.secureCookies, maxAge: 12 * 60 * 60_000, path: '/' });
    res.json({ ok: true });
  });
  app.get('/api/session', async (req, res) => {
    const token = cookie(req, 'bookora_session');
    const result = token ? await db.query('SELECT token_hash FROM sessions WHERE token_hash = $1 AND expires_at > now()', [sha(token)]) : { rows: [] };
    res.json({ authenticated: result.rows.length > 0, demo: !!options.demo });
  });
  app.use('/api', async (req, res, next) => {
    const token = cookie(req, 'bookora_session');
    if (!token || !(await db.query('SELECT token_hash FROM sessions WHERE token_hash = $1 AND expires_at > now()', [sha(token)])).rows.length) return res.status(401).json({ error: 'Please sign in.' });
    next();
  });
  app.post('/api/logout', async (req, res) => { await db.query('DELETE FROM sessions WHERE token_hash = $1', [sha(cookie(req, 'bookora_session'))]); res.clearCookie('bookora_session', { path: '/' }); res.json({ ok: true }); });
  app.get('/api/rooms', async (_req, res) => { const result = await db.query('SELECT * FROM rooms ORDER BY room_number'); res.json(result.rows); });
  app.post('/api/rooms', async (req, res) => {
    const parsed = roomSchema.safeParse(req.body); if (!parsed.success) throw new Problem(400, 'Enter a room number, type, capacity and a valid GHS rate.');
    const r = parsed.data, rate = pesewas(r.rate); if (rate < 1 || rate > 100000000) throw new Problem(400, 'Rate must be between GHS 0.01 and GHS 1,000,000.');
    try { const result = await db.query('INSERT INTO rooms (room_number, room_type, capacity, rate_pesewas) VALUES ($1,$2,$3,$4) RETURNING *', [r.roomNumber, r.roomType, r.capacity, rate]); res.status(201).json(result.rows[0]); }
    catch (e: any) { if (e.code === '23505') throw new Problem(409, 'A room with this number already exists.'); throw e; }
  });
  app.get('/api/reservations', async (_req, res) => {
    const result = await db.query(`SELECT b.id, b.room_id, b.guest_name, b.guest_email, b.guest_phone, b.guests, b.check_in::text, b.check_out::text, b.status, b.rate_pesewas, b.total_pesewas::text, b.created_at, r.room_number, r.room_type FROM reservations b JOIN rooms r ON r.id=b.room_id ORDER BY b.created_at DESC, b.id DESC LIMIT 200`);
    res.json(result.rows);
  });
  app.post('/api/reservations', async (req, res) => {
    const parsed = bookingSchema.safeParse(req.body); if (!parsed.success) throw new Problem(400, 'Please check the reservation details.');
    const b = parsed.data, start = dateNumber(b.checkIn), end = dateNumber(b.checkOut);
    const today = options.today ? options.today() : new Intl.DateTimeFormat('en-CA', { timeZone: 'Africa/Accra', year: 'numeric', month: '2-digit', day: '2-digit' }).format(new Date());
    if (start === null || end === null || b.checkIn < today || end <= start || (end - start) / 86400000 > 365) throw new Problem(400, 'Choose valid dates: check-in cannot be in the past and stays must be 1–365 nights.');
    const nights = (end - start) / 86400000;
    const row = await db.transaction(async tx => {
      // All writers in this application lock the physical room before testing availability.
      const room = (await tx.query('SELECT * FROM rooms WHERE id = $1 FOR UPDATE', [b.roomId])).rows[0];
      if (!room || b.guests > room.capacity) throw new Problem(400, 'Room not found or guest count exceeds capacity.');
      const overlap = await tx.query("SELECT id FROM reservations WHERE room_id=$1 AND status <> 'cancelled' AND check_in < $3::date AND check_out > $2::date LIMIT 1", [b.roomId, b.checkIn, b.checkOut]);
      if (overlap.rows.length) throw new Problem(409, 'This room is already reserved for those dates.');
      return (await tx.query(`INSERT INTO reservations (room_id,guest_name,guest_email,guest_phone,guests,check_in,check_out,rate_pesewas,total_pesewas) VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9) RETURNING id`, [b.roomId,b.guestName,b.email,b.phone,b.guests,b.checkIn,b.checkOut,room.rate_pesewas,room.rate_pesewas*nights])).rows[0];
    });
    res.status(201).json(row);
  });
  app.post('/api/reservations/:id/status', async (req, res) => {
    const id = Number(req.params.id), transitions: Record<string, string[]> = { check_in: ['confirmed', 'checked_in'], check_out: ['checked_in', 'checked_out'], cancel: ['confirmed', 'cancelled'] };
    const transition = transitions[req.body?.action];
    if (!Number.isSafeInteger(id) || id < 1 || !transition) throw new Problem(400, 'Invalid status change.');
    const result = await db.query('UPDATE reservations SET status=$1 WHERE id=$2 AND status=$3 RETURNING id', [transition[1], id, transition[0]]);
    if (!result.rows.length) throw new Problem(409, 'This status change is no longer available.');
    res.json({ ok: true });
  });
  app.use((err: any, _req: express.Request, res: express.Response, _next: express.NextFunction) => {
    if (!(err instanceof Problem)) console.error(err);
    res.status(err instanceof Problem ? err.status : 500).json({ error: err instanceof Problem ? err.message : 'The request could not be completed.' });
  });
  return app;
}
