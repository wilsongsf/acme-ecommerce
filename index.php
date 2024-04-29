<?php

http_response_code(405);
echo json_encode(array("message" => "Método de requisição não suportado. Especifique um caminho."));

?>