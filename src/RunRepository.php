<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use Hashids\HashidsInterface;
use PDO;

final class RunRepository implements RunRepositoryInterface
{
    private $db;

    private $hashids;

    public function __construct(PDO $db, HashidsInterface $hashids)
    {
        $this->db = $db;
        $this->hashids = $hashids;
    }

    public function getByHash(string $hash): Run
    {
        $id = $this->hashids->decode($hash)[0];

        $statement = $this->db->prepare(
            'select * from runs where id = :id limit 1'
        );

        $statement->execute([':id' => $id]);

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            throw RunNotFoundException::fromHash($hash);
        }

        return new Run(
            $data['code'],
            json_decode($data['rules'], true),
            $data['indent'],
            $data['line_ending'],
            $hash
        );
    }

    public function save(Run $run): Run
    {
        $statement = $this->db->prepare('
insert into runs (
  code,
  rules,
  indent,
  line_ending
) values (
  :code,
  :rules,
  :indent,
  :line_ending
)
        ');

        $statement->execute([
            ':code' => $run->getCode(),
            ':rules' => json_encode($run->getRules()),
            ':indent' => $run->getIndent(),
            ':line_ending' => $run->getLineEnding(),
        ]);

        return new Run(
            $run->getCode(),
            $run->getRules(),
            $run->getIndent(),
            $run->getLineEnding(),
            $this->hashids->encode($this->db->lastInsertId())
        );
    }
}
