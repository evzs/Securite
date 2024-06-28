<?php
namespace Securite;
require_once '../autoloader.php';

// TODO: remettre id comme filtre obligatoire plutot que de laisser libre choix pour certaines actions

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
        try {
            $columns = implode(", ", array_keys($record));
            $vals = ":" . implode(", :", array_keys($record));
            $query = "INSERT INTO {$table} ({$columns}) VALUES ({$vals}) ";

            $stmt = $this->pdo->prepare($query);

            foreach ($record as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }

            $stmt->execute();

            echo "Record created";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // selectionne un registre dans la table

    public function selectRecord($table, $filter)
    {
        try {
            // va verifier si `select_colum` existe dans le filtre
            if (!isset($filter['select_column'])) {
                throw new \Exception("Please select column(s)");
            }

            // recupere les colonnes selectionnees
            $columns = $filter['select_column'];

            // construit la clause SELECT selon les colonnes selectionnees
            switch ($columns) {
                case '*':
                    $selected_columns = '*'; // selection de toutes les colonnes
                    break;
                case is_array($columns):
                    $selected_columns = implode(', ', $columns); // selection specifique de colonnes
                    break;
                default:
                    throw new \Exception("Columns must be input in an array");
            }

            unset($filter['select_column']);


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

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                // si '*' est selectionne, on itere sur toutes les colonnes
                // sinon on affiche la ou les colonnes selectionnees
                $displayed_columns = ($selected_columns === '*') ? array_keys($row) : explode(', ', $selected_columns);

                foreach ($displayed_columns as $column) {
                    echo "{$column}: {$row[$column]}<br/>";
                }
            }

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateRecord($table, $record, $filter)
    {
        try {
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

            echo "Record updated";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function deleteRecord($table, $filter)
    {
        try {
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

            echo "Record deleted";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}