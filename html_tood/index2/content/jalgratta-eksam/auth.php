<?php
/**
 * Autentimise ja autoriseerimise funktsioonid
 * Haldab kasutajate sisselogimist, väljalogiist, rollide kontrollimist ja sessioone
 */

function alustaSessioon() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function onSissologitud() { // kontrollib, kas kasutaja on sisse loginud
    alustaSessioon();
    return isset($_SESSION['kasutaja_id']) && isset($_SESSION['kasutajanimi']);
}

function onAdmin() { // kontrollib, kas kasutaja on administraator
    alustaSessioon();
    return onSissologitud() && isset($_SESSION['roll']) && $_SESSION['roll'] === 'admin';
}

function kasutajaRoll() { // tagastab kasutaja rolli (admin või user)
    alustaSessioon();
    return isset($_SESSION['roll']) ? $_SESSION['roll'] : null;
}

function kasutajanimi() {
    alustaSessioon();
    return isset($_SESSION['kasutajanimi']) ? $_SESSION['kasutajanimi'] : null;
}

function kasutajaID() {
    alustaSessioon();
    return isset($_SESSION['kasutaja_id']) ? $_SESSION['kasutaja_id'] : null;
}

function sisenemine($yhendus, $kasutajanimi, $parool) { // logib kasutaja sisse ja kontrollib parooli
    $kask = $yhendus->prepare("SELECT id, kasutajanimi, parool, roll FROM kasutajad WHERE kasutajanimi = ?");
    $kask->bind_param("s", $kasutajanimi);
    $kask->execute();
    $kask->bind_result($id, $nimi, $hash, $roll);
    
    if ($kask->fetch()) {
        if (password_verify($parool, $hash)) {
            $kask->close();
            alustaSessioon();
            
            $_SESSION['kasutaja_id'] = $id;
            $_SESSION['kasutajanimi'] = $nimi;
            $_SESSION['roll'] = $roll;
            
            return [
                'edukas' => true,
                'sõnum' => 'Sisselogimine õnnestus!'
            ];
        }
    }
    
    $kask->close();
    return [
        'edukas' => false,
        'sõnum' => 'Vale kasutajanimi või parool!'
    ];
}

function valjalogimine() { // logib kasutaja välja ja hävitab sessiooni
    alustaSessioon();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    } elseif (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }

    session_destroy();
}

function registreeriKasutaja($yhendus, $kasutajanimi, $parool, $paroolKinnitatud, $roll = 'user') { // loob uue kasutaja andmebaasi
    if (empty($kasutajanimi) || strlen($kasutajanimi) < 3) {
        return [
            'edukas' => false,
            'sõnum' => 'Kasutajanimi peab olema vähemalt 3 tähemärki!'
        ];
    }
    
    if (empty($parool) || strlen($parool) < 5) {
        return [
            'edukas' => false,
            'sõnum' => 'Parool peab olema vähemalt 5 tähemärki!'
        ];
    }
    
    if ($parool !== $paroolKinnitatud) {
        return [
            'edukas' => false,
            'sõnum' => 'Paroolid ei ühti!'
        ];
    }
    
    $kask = $yhendus->prepare("SELECT id FROM kasutajad WHERE kasutajanimi = ?");
    $kask->bind_param("s", $kasutajanimi);
    $kask->execute();
    $kask->store_result();
    
    if ($kask->num_rows > 0) {
        $kask->close();
        return [
            'edukas' => false,
            'sõnum' => 'See kasutajanimi on juba kasutusel!'
        ];
    }
    $kask->close();
    
    if (!in_array($roll, ['admin', 'user'])) {
        $roll = 'user';
    }
    
    $hash = password_hash($parool, PASSWORD_DEFAULT);
    
    $kask = $yhendus->prepare("INSERT INTO kasutajad (kasutajanimi, parool, roll) VALUES (?, ?, ?)");
    $kask->bind_param("sss", $kasutajanimi, $hash, $roll);
    
    if ($kask->execute()) {
        $kask->close();
        return [
            'edukas' => true,
            'sõnum' => 'Kasutaja edukalt registreeritud!'
        ];
    } else {
        $kask->close();
        return [
            'edukas' => false,
            'sõnum' => 'Viga registreerimisel!'
        ];
    }
}

function nouaSisselogimist($redirect = 'login.php') { // suunab login lehele, kui kasutaja pole sisse loginud
    if (!onSissologitud()) {
        header("Location: $redirect");
        exit();
    }
}

function nouaAdminRolli($redirect = 'index.php') { // suunab avalehele, kui kasutaja pole admin
    if (!onAdmin()) {
        header("Location: $redirect");
        exit();
    }
}

function suunaKuiSissologitud($redirect = 'index.php') { // suunab avalehele, kui kasutaja on juba sisse loginud
    if (onSissologitud()) {
        header("Location: $redirect");
        exit();
    }
}