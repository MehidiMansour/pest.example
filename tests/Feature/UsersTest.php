<?php
it('has users page', function () {
    $this->get('/')->assertStatus(200);
});