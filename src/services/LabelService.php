<?php

namespace Services;

use Exception;
use PDO;
use PDOException;
use Database\DbService;

class LabelService
{
    private PDO $pdo;

    function __construct(PDO $pdo = null)
    {
        if (empty($pdo)) {
            $db = new DbService();
            $this->pdo = $db->connect();
        } else {
            $this->pdo = $pdo;
        }
    }

    /**
     * Checks tags for compliance and checks whether the entity exists
     */
    private function validate(string $entity, int $id, array $labels = null)
    {
        if (!empty($labels)) {
            try {
                $in = join(',', array_fill(0, count($labels), '?'));
                $count = count($labels);
                $query = "SELECT (SELECT count(DISTINCT id) FROM label WHERE name IN ($in)) = $count";
                $sth = $this->pdo->prepare($query);
                $sth->execute($labels);
                $result = $sth->fetch(PDO::FETCH_COLUMN);
                if (empty($result)) {
                    throw new \Exception("Invalid labels");
                }
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }

        try {
            $sth = $this->pdo->prepare("SELECT EXISTS(SELECT 1 FROM $entity WHERE id = ?)");
            $sth->bindParam(1, $id);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_COLUMN);
            if (empty($result)) {
                throw new \Exception("$entity id:$id not found");
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Replaces all existing tags with the specified ones
     */
    public function replaceEntityLabels(string $entity, int $id, array $labels): bool
    {
        if (empty($labels)) {
            throw new Exception("Null array");
        }

        $this->validate($entity, $id, $labels);
        $stringArray = implode(',', $labels);

        try {
            $sth = $this->pdo->prepare("UPDATE $entity SET labels = '{{$stringArray}}' WHERE id = ? ");
            $sth->bindParam(1, $id);
            $sth->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return true;
    }

    /**
     * Adds tags to the entity, if at least one tag already exists throws an exception
     */
    public function addLabelsToEntity(string $entity, int $id, array $labels): bool
    {
        if (empty($labels)) {
            throw new Exception("Null array");
        }

        $this->validate($entity, $id, $labels);
        $entityLabels = $this->getEntityLabels($entity, $id);
        $check = count(array_intersect($labels, $entityLabels));

        if ($check) {
            throw new Exception("Some tags are already exist");
        }

        $stringArray = implode(',', $labels);

        try {
            $sth = $this->pdo->prepare("UPDATE $entity SET labels = array_append(labels,'$stringArray') WHERE id = $id");
            $sth->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return true;
    }
    /**
     * Deletes the specified tags from the entity, if the tag does not exist throws an exception
     */
    public function deleteEntityLabels(string $entity, int $id, array $labels): bool
    {
        if (empty($labels)) {
            throw new Exception("Null array");
        }

        $this->validate($entity, $id);
        $entityLabels = $this->getEntityLabels($entity, $id);
        $check = count(array_diff($labels, $entityLabels));

        if ($check) {
            throw new Exception("Some tags are missing");
        }

        $stringArray = implode(',', $labels);

        try {
            $sth = $this->pdo->prepare("UPDATE $entity SET labels = array_remove(labels,'$stringArray') WHERE id = $id");
            $sth->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return true;
    }
    /**
     * Returns an array of entity tags
     */
    public function getEntityLabels(string $entity, int $id): array
    {
        $this->validate($entity, $id);

        try {
            $sth = $this->pdo->prepare("SELECT array_to_json(labels) FROM $entity WHERE id = $id");
            $sth->execute();
            $res = $sth->fetch(PDO::FETCH_COLUMN);
            return json_decode($res);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
