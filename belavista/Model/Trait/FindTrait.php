<?php


trait FindTrait{
    public function findById($id)
    {
        $cmd = $this->conexao->query(
            "SELECT 
                *
            FROM
                $this->model
            WHERE
                id = $id
            "
        );

        if($cmd->rowCount() > 0)
        {

            return self::messageWithData(201,'Dados encontrados', $cmd->fetchAll(PDO::FETCH_ASSOC));
        }

        return false;
    }
}