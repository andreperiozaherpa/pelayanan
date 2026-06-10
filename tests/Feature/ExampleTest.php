<?php

test('the root page returns siberugo landing page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('the admin dashboard redirects to login', function () {
    $response = $this->get('/dashboard/admin');

    $response->assertRedirect('/login');
});
