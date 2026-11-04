// SPDX-License-Identifier: GPL-2.0-or-later
import { PGlite } from '@electric-sql/pglite';
import pg from 'pg';
import { mkdir } from 'node:fs/promises';

type Row = Record<string, any>;
export interface Queryable { query(sql: string, params?: unknown[]): Promise<{ rows: Row[]; rowCount?: number | null }> }
export interface Database extends Queryable { transaction<T>(fn: (tx: Queryable) => Promise<T>): Promise<T>; close(): Promise<void> }

export async function createDatabase(url?: string, dataDir = 'memory://'): Promise<Database> {
  if (url) {
    const pool = new pg.Pool({ connectionString: url });
    return {
      query: async (sql, params = []) => pool.query(sql, params),
      transaction: async fn => {
        const client = await pool.connect();
        try { await client.query('BEGIN'); const result = await fn(client as Queryable); await client.query('COMMIT'); return result; }
        catch (e) { await client.query('ROLLBACK'); throw e; } finally { client.release(); }
      },
      close: async () => { await pool.end(); },
    };
  }
  if (dataDir !== 'memory://') await mkdir(dataDir, { recursive: true });
  const db = new PGlite(dataDir);
  // PGlite is embedded and used only for the local prototype. Its transaction API serializes access.
  return {
    query: async (sql, params = []) => db.query(sql, params) as Promise<{ rows: Row[] }>,
    transaction: async fn => db.transaction(async tx => fn({ query: async (sql, params = []) => tx.query(sql, params) as Promise<{ rows: Row[] }> })),
    close: async () => { await db.close(); },
  };
}

export async function migrate(db: Database) {
  await db.query(`CREATE TABLE IF NOT EXISTS rooms (
    id SERIAL PRIMARY KEY, room_number VARCHAR(40) UNIQUE NOT NULL, room_type VARCHAR(80) NOT NULL,
    capacity INTEGER NOT NULL CHECK (capacity BETWEEN 1 AND 20), rate_pesewas INTEGER NOT NULL CHECK (rate_pesewas BETWEEN 1 AND 100000000),
    created_at TIMESTAMPTZ NOT NULL DEFAULT now()
  )`);
  await db.query(`CREATE TABLE IF NOT EXISTS reservations (
    id SERIAL PRIMARY KEY, room_id INTEGER NOT NULL REFERENCES rooms(id), guest_name VARCHAR(120) NOT NULL,
    guest_email VARCHAR(190) NOT NULL DEFAULT '', guest_phone VARCHAR(40) NOT NULL DEFAULT '', guests INTEGER NOT NULL,
    check_in DATE NOT NULL, check_out DATE NOT NULL, status VARCHAR(20) NOT NULL DEFAULT 'confirmed' CHECK (status IN ('confirmed','checked_in','checked_out','cancelled')),
    rate_pesewas INTEGER NOT NULL, total_pesewas BIGINT NOT NULL, created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    CHECK (check_in < check_out), CHECK (guests > 0)
  )`);
  await db.query("ALTER TABLE reservations ADD COLUMN IF NOT EXISTS source VARCHAR(16) NOT NULL DEFAULT 'reception'");
  await db.query('CREATE INDEX IF NOT EXISTS reservation_availability ON reservations (room_id, check_in, check_out)');
  await db.query(`CREATE TABLE IF NOT EXISTS sessions (token_hash TEXT PRIMARY KEY, expires_at TIMESTAMPTZ NOT NULL)`);
}
