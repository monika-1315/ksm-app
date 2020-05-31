<?php

$I = new AcceptanceTester($scenario);
$I->am('user'); // actor's role
$I->wantTo('log in'); // feature to test
$I->amOnPage('/login');
$I->see('Zaloguj się');
$I->fillField('#email','moniusiar@gmail.com');
$I->fillField('#password','password');
$I->click('#log-in');
$I->waitForElement('#getMessages',30);
$I->see('Witaj');
$I->waitForElement('#panel',30);
$I->click('Panel sterowania');
$I->waitForElement('#edit',30);
$I->see('Twoje konto');

$I->click('Wyloguj się');
$I->waitForElement('#email',30);