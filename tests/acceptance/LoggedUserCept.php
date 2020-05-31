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
$I->wantTo('see the control panel');
$I->waitForElement('#panel',30);
$I->click('Panel sterowania');
$I->waitForElement('#new-mes',30);
$I->see('Twoje konto');
$I->see('Ogłoszenia:');
$I->see('Zarządzaj członkami:');
$I->see('Zarządzaj oddziałami:');
$I->wantTo('edit my data');
$I->click('Edytuj swoje dane');
$I->waitForElement('.form-group',30);
$I->see('Edytuj swoje dane osobowe');
$I->click('Zapisz');

$I->wantTo('see statistics');
$I->amOnPage('/panel');
$I->waitForElement('#edit_data',30);
$I->see('Zarządzaj');
$I->click('Statystyki');
$I->waitForElement('.small',30);
$I->see('Statystyki dotyczące liczby członków w oddziałach');
$I->see('Statystyki autoryzacji użytkowników dla poszczególnych oddziałów:');
$I->see('Bolesławiec');

$I->wantTo('see all the divisions');
$I->amOnPage('/panel');
$I->click('Zarządzaj oddziałami');
$I->waitForElement('.edit',30);
$I->see('Bolesławiec');

$I->wantTo('add new division');
$I->click('Dodaj nowy');
$I->waitForElement('.form-group',30);
$I->fillField('#town','Chojnów');
$I->fillField('#parish','p.w. św. Michała Archanioła');
$I->click('Zapisz');
$I->waitForElement('.v-toaster',10);

$I->wantTo('add new message');
$I->click('Panel sterowania');
$I->waitForElement('#new-mes',30);
$I->click('Nowa wiadomość');
$I->waitForElement('.form-group',30);
$I->fillField('#title','Test');
$I->fillField('#body','Testowa wiadomość');
$I->click('Zapisz wiadomość');
$I->waitForElement('.v-toaster',10);

$I->wantTo('delete my messsage');
$I->click('Panel sterowania');
$I->waitForElement('#new-mes',30);
$I->click('Edytuj swoje ogłoszenia');
$I->waitForElement('.editbtn',30);
$I->click('Edytuj');
$I->waitForElement('.form-group',30);
$I->click('Usuń');
$I->waitForElement('.v-toaster',10);

$I->click('Wyloguj się');
$I->waitForElement('#email',30);