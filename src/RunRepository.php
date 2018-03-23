<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use PDO;

final class RunRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getById(int $id): Run
    {
        $statement = $this->db->prepare('select * from runs where id = :id limit 1');

        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            throw RunNotFoundException::fromId($id);
        }

        return new Run(
            $data['code'],
            $data['result'],
            json_decode($data['rules']),
            $id
        );
    }

    public function save(Run $run): Run
    {
        $statement = $this->db->prepare('insert into runs (id, code, result, rules) values (:id, :code, :result, :rules)');

        $statement->bindValue(':id', $run->getId());
        $statement->bindValue(':code', $run->getCode());
        $statement->bindValue(':result', $run->getResult());
        $statement->bindValue(':rules', json_encode($run->getRules()));

        $statement->execute();

        return new Run(
            $run->getCode(),
            $run->getResult(),
            $run->getRules(),
            (int) $this->db->lastInsertId()
        );
    }
}
