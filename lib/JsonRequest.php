<?php


class JsonRequest
{
    public function getData(): array
    {
        $json = file_get_contents('php://input');

        return $data = json_decode($json);

    }
}