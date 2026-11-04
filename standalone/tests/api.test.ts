// SPDX-License-Identifier: GPL-2.0-or-later
import { test } from 'node:test';
import assert from 'node:assert/strict';
import { createDatabase, migrate } from '../server/db';
import { createApp } from '../server/app';

test('authenticated room and reservation workflows, validation and overlapping writes', async () => {
  const db = await createDatabase(); await migrate(db);
  const app = createApp(db, {password:'a-secure-test-password', today:()=> '2030-01-01'});
  const server = app.listen(0, '127.0.0.1');
  await new Promise<void>(resolve => server.once('listening', resolve));
  const addr = server.address(); if (!addr || typeof addr === 'string') throw Error('no server');
  const base = `http://127.0.0.1:${addr.port}/api`;
  let cookie = '';
  const req = async (path:string, method='GET', body?:unknown, header=true) => fetch(base+path, {method,headers:{...(header?{'X-Bookora-Request':'1'}:{}),'Content-Type':'application/json',Cookie:cookie},...(body===undefined?{}:{body:JSON.stringify(body)})});
  try {
    assert.equal((await req('/rooms')).status, 401);
    assert.equal((await req('/login','POST',{username:'admin',password:'wrong'})).status, 401);
    assert.equal((await req('/login','POST',{username:'admin',password:'a-secure-test-password'},false)).status, 403);
    const login = await req('/login','POST',{username:'admin',password:'a-secure-test-password'}); assert.equal(login.status, 200);
    cookie = login.headers.get('set-cookie')!.split(';')[0]; assert.match(login.headers.get('set-cookie')!, /HttpOnly/);
    const room = {roomNumber:'101',roomType:'Deluxe',capacity:2,rate:'120.50'};
    assert.equal((await req('/rooms','POST',{...room,rate:'1.001'})).status,400);
    const r = await req('/rooms','POST',room); assert.equal(r.status,201); const created = await r.json() as {id:number;rate_pesewas:number}; assert.equal(created.rate_pesewas,12050);
    assert.equal((await req('/rooms','POST',room)).status,409);
    const booking = {roomId:created.id,guestName:'Test Guest',email:'',phone:'',guests:2,checkIn:'2030-01-02',checkOut:'2030-01-04'};
    assert.equal((await req('/reservations','POST',{...booking,guests:3})).status,400);
    assert.equal((await req('/reservations','POST',{...booking,checkIn:'2030-02-30'})).status,400);
    assert.equal((await req('/reservations','POST',{...booking,checkIn:'2029-12-31'})).status,400);
    assert.equal((await req('/reservations','POST',{...booking,checkOut:'2031-01-04'})).status,400);
    const responses = await Promise.all([req('/reservations','POST',booking),req('/reservations','POST',booking)]);
    assert.deepEqual(responses.map(x=>x.status).sort(),[201,409]);
    const list = await (await req('/reservations')).json() as {id:number;total_pesewas:string}[];
    assert.equal(list.length,1); assert.equal(list[0].total_pesewas,'24100');
    assert.equal((await req('/reservations','POST',{...booking,checkIn:'2030-01-04',checkOut:'2030-01-05'})).status,201);
    assert.equal((await req(`/reservations/${list[0].id}/status`,'POST',{action:'check_out'})).status,409);
    assert.equal((await req(`/reservations/${list[0].id}/status`,'POST',{action:'check_in'})).status,200);
    assert.equal((await req(`/reservations/${list[0].id}/status`,'POST',{action:'cancel'})).status,409);
    assert.equal((await req(`/reservations/${list[0].id}/status`,'POST',{action:'check_out'})).status,200);
    assert.equal((await req('/logout','POST',{})).status,200);
    assert.equal((await req('/rooms')).status,401);
  } finally { await new Promise<void>(resolve=>server.close(()=>resolve())); await db.close(); }
});
