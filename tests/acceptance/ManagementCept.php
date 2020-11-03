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
$I->waitForElement('h4',30);
$I->waitForElementClickable('#edit_data',30);
$I->see('Edytuj swoje dane');
// $I->wait(3);
$I->see('Ogłoszenia:');
$I->see('Zarządzaj członkami:');
$I->see('Zarządzaj oddziałami:');

$I->wantTo('edit my data');
$I->click('#edit_data');
$I->waitForElement('.form-group',30);
$I->see('Edytuj swoje dane osobowe');
$I->fillField('#email','moniusiar@gmail.com');
$I->click('Zapisz');
$I->waitForElement('.v-toaster',30);

$I->wantTo('see all the divisions');
$I->amOnPage('/panel');
$I->click('#manage-div-btn');
$I->waitForElement('.edit',30);
$I->see('Bolesławiec');

$I->wantTo('add new division');
$I->click('Dodaj nowy');
$I->waitForElement('.form-group',30);
$I->fillField('#town','Chojnów');
$I->fillField('#parish','p.w. św. Michała Archanioła');
$I->fillField('#email','q@mail.com');
$I->click('Zapisz');
$I->waitForElement('.v-toaster',10);

$I->wantTo('see statistics');
$I->amOnPage('/panel');
$I->waitForElement('#stats-btn',30);
$I->click('#stats-btn');
$I->waitForElement('.small',30);
$I->see('Statystyki dotyczące liczby członków w oddziałach');
$I->see('Statystyki autoryzacji użytkowników dla poszczególnych oddziałów:');
$I->see('Bolesławiec');

$I->wantTo('add new message');
$I->click('Panel sterowania');
$I->waitForElement('#new-mes',30);
$I->click('#new-mes');
$I->waitForElement('.form-group',30);
$I->fillField('#title','Test');
$I->fillField('#body','Testowa wiadomość');
$I->click('Zapisz wiadomość');
$I->waitForElement('.v-toaster',30);

$I->wantTo('delete my messsage');
$I->click('Panel sterowania');
$I->waitForElement('#new-mes',30);
$I->click('#editmes-btn');
$I->waitForElement('.editbtn',30);
$I->click('Edytuj');
$I->waitForElement('.form-group',30);
$I->click('Usuń');
$I->waitForElement('.v-toaster',10);

$I->click('Wyloguj się');
$I->waitForElement('#email',30);