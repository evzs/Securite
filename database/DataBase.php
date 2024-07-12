<?php
namespace Securite\database;

/* TODO: handle execptions with response codes for all the other methods et enlever tous les echos zzz
TODO: supprimer try catch
TODO: ajouter OR condition a select update et delete record
TODO: creer methodes pour clauses set et where ?
TODO: whitelist operateurs SQL ?
*/
class DataBase
{
    private $connection;

    public function __construct()
    {
        $this->pdo = (new Connection())->getConnection();
    }


    // insere un nouveau registre dans la table
    public function addRecord($table, $record)
    {
        $columns = implode(", ", array_keys($record));
        $vals = ":" . implode(", :", array_keys($record));
        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$vals}) ";

        $stmt = $this->pdo->prepare($query);

        foreach ($record as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        $stmt->execute();
    }

    // selectionne un registre dans la table
    public function selectRecord($table, $columns, $filter)
    {
        // va verifier si `columns` est un tableau
        if (!is_array($columns)) {
            throw new \Exception("Column(s) must be given as an array");
        }

        // recupere les colonnes selectionnees
        $selected_columns = implode(', ', $columns);


        $query = "SELECT {$selected_columns} FROM {$table} WHERE ";
        $conditions = [];
        foreach ($filter as $key => $val) {
            $conditions[] = "{$key} = :{$key}";
        }
        $query .= implode(' AND ', $conditions);

        $stmt = $this->pdo->prepare($query);

        foreach ($filter as $key => &$val) {
            $stmt->bindParam(":{$key}", $val);
        }

        $stmt->execute();

        // retourne le statement sous forme d'array associatif
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // met a jour un registre dans la table
    public function updateRecord($table, $record, $filter)
    {
        // le tableau `set_array` sert a stocker les clauses SET
        $set_array = [];
        foreach ($record as $column => $value) {
            $set_array[] = "{$column} = :{$column}";
        }
        $set_clause   = implode(', ', $set_array);

        // le tableau `filter_array` sert a stocker les clauses WHERE
        $filter_array = [];
        foreach ($filter as $column => $value) {
            $filter_array[] = "{$column} = :where_{$column}";
        }
        $filter_clause = implode(' AND ', $filter_array);

        $query = "UPDATE {$table} SET {$set_clause} WHERE {$filter_clause}";

        $stmt = $this->pdo->prepare($query);

        foreach ($record as $column => &$value) {
            $stmt->bindParam(":{$column}", $value);
        }

        foreach ($filter as $column => &$value) {
            $stmt->bindParam(":where_{$column}", $value);
        }
        $stmt->execute();
    }

    // supprime un registre dans la table
    public function deleteRecord($table, $filter)
    {
        $filter_array = [];
        foreach ($filter as $column => $value) {
            $filter_array[] = "{$column} = :{$column}";
        }
        $filter_clause = implode(' AND ', $filter_array);

        $query = "DELETE FROM {$table} WHERE {$filter_clause}";

        $stmt = $this->pdo->prepare($query);

        foreach ($filter as $column => &$value) {
            $stmt->bindParam(":{$column}", $value);
        }

        $stmt->execute();
    }
}