<?php
    function base64url_encode($data) {
        // strtr -> zmiana + na -, / na _
        // rtrirm -> usuwa znaki =
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    function generateJwt($userId, $email, $role, $secret) {
        $header = base64url_encode(json_encode(['alg'=>'HS256', 'typ'=>'JWT']));
        $payload = base64url_encode(json_encode([
            'user_id'=>$userId,
            'email' => $email,
            'role'=>$role,
            'iat'=>time(),
            'exp'=>time()+3600
        ]));
        $signature = base64url_encode(hash_hmac('sha256',$header . '.' . $payload,$secret,true));
        return $header . '.' . $payload . '.' . $signature;
    }

    function verifyJwt($token, $secret) {
        $parts = explode('.', $token);
        if(count($parts)!==3)
        {
            return null;        
        }

        [$header, $payload, $signature] = $parts;

        $expectedSignature = base64url_encode(hash_hmac('sha256', $header . '.' . $payload,$secret, true));

        if($signature !== $expectedSignature) {
            return null;
        }

        $data = json_decode(base64_decode($payload), true);
 
        if($data['exp'] < time()) {
            return null;
        }

        return $data;
    }
?>