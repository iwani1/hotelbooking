// SPDX-License-Identifier: GPL-2.0-or-later
import { createDatabase, migrate } from './db';
import { createApp } from './app';

const demo = process.env.BOOKORA_DEMO === '1';
const password = process.env.BOOKORA_ADMIN_PASSWORD || (demo ? 'demo-bookora-2026' : '');
if (!password || password.length < 12) throw new Error('Set BOOKORA_ADMIN_PASSWORD (at least 12 characters), or explicitly opt into BOOKORA_DEMO=1.');
const db = await createDatabase(process.env.DATABASE_URL, process.env.PGLITE_DIR || './.data/bookora');
await migrate(db);
if (demo && (await db.query('SELECT id FROM rooms LIMIT 1')).rows.length === 0) {
  const rooms = [ ['101','Deluxe King',2,85000], ['102','Classic Queen',2,65000], ['103','Deluxe King',2,85000], ['201','Executive Suite',3,140000], ['202','Classic Queen',2,65000], ['203','Family Suite',4,160000], ['301','Executive Suite',3,140000], ['302','Classic Queen',2,65000] ];
  for (const r of rooms) await db.query('INSERT INTO rooms (room_number,room_type,capacity,rate_pesewas) VALUES ($1,$2,$3,$4)', r);
  const names = ['Ama Mensah','Kwame Asante','Nana Boateng','Abena Owusu','Kofi Addo'];
  for (let i=0;i<names.length;i++) await db.query(`INSERT INTO reservations (room_id,guest_name,guests,check_in,check_out,status,rate_pesewas,total_pesewas) SELECT id,$1,2,current_date + $2::integer,current_date + $3::integer,$4,rate_pesewas,rate_pesewas * ($3::integer-$2::integer) FROM rooms WHERE room_number=$5`, [names[i], i-2, i+2, i<2 ? 'checked_in' : 'confirmed', rooms[i][0]]);
}
const app = createApp(db, { password, demo, secureCookies: process.env.SECURE_COOKIES === '1' });
const server = app.listen(Number(process.env.PORT || 3001), '0.0.0.0', () => console.log(`Bookora API listening on port ${process.env.PORT || 3001}${demo ? ' (demo mode)' : ''}`));
for (const signal of ['SIGINT','SIGTERM'] as const) process.on(signal, () => server.close(() => { void db.close().finally(() => process.exit()); }));
