<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    // $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
    //     // Sample log message
    //     $container->get('logger')->info("Slim-Skeleton '/' route");

    //     // Render index view
    //     return $container->get('renderer')->render($response, 'index.phtml', $args);
    // });
    $app->post("/pengguna", function (Request $request, Response $response){
        $body = $request->getParsedBody();
        $sql = "SELECT * FROM pengguna where username=:username && password = md5(:password)";
        
        $stmt = $this->db->prepare($sql);
        $data = [
            ":username" => $body["username"],
            ":password" => $body["password"]            
        ];
        $stmt->execute($data);
        $result = $stmt->fetch();
        if($result == false ){
            return $response->withJson(["status" => "failed", "data" => $result], 200);
        }
        else{
            return $response->withJson(["status" => "success", "username" => $result['username'],"nama" => $result['nama'],"role" => $result['status']], 200);
        }
        
    });
    $app->get("/quiz", function (Request $request, Response $response){
        $sql = "SELECT * FROM kuis";
        $stmt = $this->db->prepare($sql);
        
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
        
    });
    $app->post("/soal", function (Request $request, Response $response){
        $body = $request->getParsedBody();
        $sql = "SELECT s.id,s.id_kuis,s.point,s.soal,s.tingkat_kesulitan,s.tipe_soal,j.isi from soal s inner join jawaban j ON s.id = j.id_soal  && j.valid = true && s.id_kuis =:id";
        $stmt = $this->db->prepare($sql);
        $data = [
            ":id" => $body["id"]           
        ];
        $stmt->execute($data);
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    
    });
    $app->post("/jawaban", function (Request $request, Response $response){
        $body = $request->getParsedBody();
        $sql = "SELECT * from jawaban j where j.id_soal = :id";
        $stmt = $this->db->prepare($sql);
        $data = [
            ":id" => $body["id"]           
        ];
        $stmt->execute($data);
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    
    });
};
