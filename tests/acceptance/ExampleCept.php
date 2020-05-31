<?php

$I = new AcceptanceTester($scenario);
$I->am('user'); // actor's role
$I->wantTo('view homepage'); // feature to test
$I->amOnPage('/');
$I->see('Zaloguj się'); // replace this with something on your homepage
$I->amOnPage('/');