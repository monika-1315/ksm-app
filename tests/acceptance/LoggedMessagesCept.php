<?php

$I = new AcceptanceTester($scenario);
$I->am('user'); // actor's role
$I->wantTo('log in'); // feature to test
$I->amOnPage('/login');
$I->see('Zaloguj się');
$I->click('#log-in');
$I->waitForElement('.text-danger',15);
$I->see('Pole email jest wymagane.');
$I->see('Pole password jest wymagane.');
$I->fillField('#email','moniusiar@gmail.com');
$I->fillField('#password','password');
$I->click('#log-in');
$I->waitForElement('#getMessages',30);
$I->see('Witaj');
$I->wantTo('see newest messages');
$I->see('Najnowsze ogłoszenia:');
$I->click('Następna strona');
$I->wait(1);
$I->click('Oddział');
$I->wait(1);
$I->click('Od_Zarządu');
$I->wait(1);
$I->click('Wszystkie');
$I->wait(1);
$I->see('Witaj Monika');

$I->wantTo('log out');
$I->click('Wyloguj się');
$I->waitForElement('#email',30);
$I->see('Zaloguj się');