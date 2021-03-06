<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\NewUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\IdRequest;
use Illuminate\Http\Request;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function getUser(Request $request)
    {
        $user = JWTAuth::toUser($request->only('token'));

        return response()->json([$user]);
    }

    public function authorizeUser(IdRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        if ($logged_user->is_leadership == 0 || $logged_user->is_authorized == 0)
            return response()->json([
                'success' => false
            ]);
        $user = User::find($request->get('id'));
        $user->is_authorized = 1;
        $user->save();
        Mail::sendById(
            $request->get('id'),
            'Twoje konto zostało zatwierdzone.',
            'Witaj!<br>Kierownictwo Oddziału właśnie zatwierdziło Twoje konto w aplikacji Katolickiego Stowarzyszenia Młodzieży Diecezji Legnickiej. Teraz masz już pełen dostęp do funkcjonalności platformy, komunikatów, wydarzeń ;)'
        );
        return response()->json([
            'success' => true
        ]);
    }
    public function discardUser(IdRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        if ($logged_user->is_leadership == 0 || $logged_user->is_authorized == 0)
            return response()->json([
                'success' => false
            ]);

        $user = User::find($request->get('id'));

        Mail::sendById(
            $request->get('id'),
            'Twoje konto zostało odrzucone.',
            'Witaj!<br>Niestety, Kierownictwo wybranego przez Ciebie oddziału nie rozpoznało Cię i usunęło Twoje konto z aplikacji. Spróbuj skontaktować się ze swoim oddziałem. Dziękujemy, że chciałeś korzystać z naszej aplikacji!'
        );
        $user->delete();
        return response()->json([
            'success' => true
        ]);
    }

    public function getUnauthorizedUsers(Request $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        if ($logged_user->is_leadership == 0 || $logged_user->is_authorized == 0)
            return response('',401);

        $data = User::where('division', $logged_user->division)
            ->where('is_authorized', 0)
            ->orderby('id', 'desc')
            ->select('id', 'name', 'surname')
            ->get();
        return response()->json($data);
    }

    public function getAuthorizedUsers(Request $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        if ($logged_user->is_management == 0 || $logged_user->is_authorized == 0)
            return response('',401);

        $data = User::where('is_authorized', 1)
            ->get();
        return response()->json($data);
    }

    public function getAuthorizedUsersDiv(Request $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        if ($logged_user->is_leadership == 0 || $logged_user->is_authorized == 0)
            return response('',401);

        $data = User::where('division', $logged_user->division)
            ->where('is_authorized', 1)
            ->get();
        return response()->json($data);
    }

    public static function getRecipients($division, $group)
    {
        $recipients = [];
        if ($division == 0 || $division == null || $division == -1) {
            if ($group == 0 || $group == null) { //everyone
                $recipients = User::where('is_authorized', '=', 1)
                    ->where('want_messages', '=', 1)
                    ->select('email')
                    ->get();
            } else if ($group == 2) { //leaderships only
                $recipients = User::where('is_authorized', '=', 1)
                    ->where('is_leadership', '=', 1)
                    ->where('want_messages', '=', 1)
                    ->select('email')
                    ->get();
            }
        } else {
            $recipients = User::where('is_authorized', '=', 1)
                ->where('division', '=', $division)
                ->where('want_messages', '=', 1)
                ->select('email')
                ->get();
        }
        return $recipients;
    }

    public function updateUser(UserRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        $user = User::find($logged_user->id);
        $user->name = $request->get('name');
        $user->surname = $request->get('surname');
        $user->email = $request->get('email');
        if ($request->get('password') == $request->get('confirmPassword') && $request->get('password') !== null)
            $user->password = bcrypt($request->get('password'));
        $user->birthdate = $request->get('birthdate');
        $user->division = $request->get('division');
        $user->want_messages = $request->get('wantMessages');
        $user->is_leadership = $request->get('is_leadership');
        $user->is_management = $request->get('is_management');
        $user->save();
        return response()->json([
            'success' => true
        ]);
    }

    public function changeLeadership(IdRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        if ($logged_user->is_leadership == 0 || $logged_user->is_authorized == 0)
            return response()->json([
                'success' => false
            ]);

        $user = User::find($request->get('id'));

        if ($user->is_leadership == 1) {
            $user->is_leadership = 0;
            Mail::sendById(
                $request->get('id'),
                'Zmiana uprawnień Twojego konta.',
                'Witaj!<br>Twojemu kontu w aplikacji KSM DL zostały właśnie odebrane uprawnienia członka Kierownictwa.'
            );
        } else {
            $user->is_leadership = 1;
            Mail::sendById(
                $request->get('id'),
                'Zmiana uprawnień Twojego konta.',
                'Witaj!<br>Twojemu kontu w aplikacji KSM DL zostały właśnie nadane uprawnienia członka Kierownictwa.'
            );
        }
        $user->save();
        return response()->json([
            'success' => true
        ]);
    }

    public function changeManagement(IdRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        if ($logged_user->is_management == 0 || $logged_user->is_authorized == 0)
            return response()->json([
                'success' => false
            ]);

        $user = User::find($request->get('id'));

        if ($user->is_management == 1) {
            $user->is_management = 0;
            Mail::sendById(
                $request->get('id'),
                'Zmiana uprawnień Twojego konta.',
                'Witaj!<br>Twojemu kontu w aplikacji KSM DL zostały właśnie odebrane uprawnienia członka Zarządu Diecezjalnego.'
            );
        } else {
            $user->is_management = 1;
            Mail::sendById(
                $request->get('id'),
                'Zmiana uprawnień Twojego konta.',
                'Witaj!<br>Twojemu kontu w aplikacji KSM DL zostały właśnie nadane uprawnienia członka Zarządu Diecezjalnego.'
            );
        }
        $user->save();
        return response()->json([
            'success' => true
        ]);
    }

    public function addUser(NewUserRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        if ($logged_user->is_authorized == 0 || ($logged_user->is_leadership == 0 &&$logged_user->is_management == 0 ))
            return response()->json([
                'success' => false
            ]);

        $user = new User();

        $user->name = $request->get('name');
        $user->surname = $request->get('surname');
        $user->email = $request->get('email');
        $user->birthdate = $request->get('birthdate');
        $user->division = $request->get('division');
        $user->want_messages = 0;
        $leader = $request->get('is_leadership');
        $user->is_leadership = $leader;
        $user->is_authorized = $request->get('is_authorized');

        $password = UsersController::randomString();
        $user->password = bcrypt($password);
        $user->save();

        Mail::send($request->get('email'), 'Utworzono konto w aplikacji KSM DL', 'Witaj ' . $request->get('name') . '!<br>'
            . 'Zostało właśnie utworzone dla Ciebie konto w Aplikacji KSM DL. <br>Możesz zalogować się na stronie <a href="app.ksm.legnica.pl">app.ksm.legnica.pl</a> '
            . 'używając maila ' . $request->get('email') . '. Twoje hasło to <b>' . $password . '</b>. Po zalogowaniu zmień hasło w zakładce "Edytuj sane osobowe". '
            . 'Zalogowanie się oznacza wyrażenie zgody na przetwarzanie Twoich danych przez Katolickie Stowarzyszenie Młodzieży Diecezji Legnickiej.'
            . '<br>Jeżeli nie chcesz/nie wyrażasz zgody na posiadanie konta w aplikacji, odpisz na tego maila, a usuniemy Twoje dane.');

        return response()->json([

            'success' => true
        ]);
    }

    static function randomString(
        int $length = 12,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    public function newPassword(EmailRequest $request)
    {
        $user = User::where('email', '=', $request->only('email'))
            ->get();
        if (sizeof($user) > 0) {
            $password = UsersController::randomString();
            $user_ = User::find($user[0]->id);
            $user_->password = bcrypt($password);
            $user_->save();
            $sent = Mail::sendById(
                $user[0]->id,
                'Zmiana hasła do Twojego konta.',
                'Witaj!<br>Na żądanie zostało zresetowane hasło do Twojego konta. Twoje nowe hasło to:<br><b>' . $password
                    . '</b><br> Po zalogowaniu zmień hasło w zakładce "Edytuj sane osobowe".'
            );
            return response()->json([
                'success' => true,
                'sent' => $sent
            ]);
        } else
            return response()->json([
                'success' => false
            ]);
    }
}
