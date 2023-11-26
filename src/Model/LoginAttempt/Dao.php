<?php

namespace ExtendMate\Bundle\UserLoginHistoryBundle\Model\LoginAttempt;

use Pimcore\Model\Dao\AbstractDao;
use Pimcore\Model\Exception\NotFoundException;

class Dao extends AbstractDao
{
    protected string $tableName = 'bundle_emulh_login_attempts';

    /**
     * get vote by id
     *
     * @throws \Exception
     */
    public function getById(?int $id = null): void
    {
        if ($id !== null) {
            $this->model->setId($id);
        }

        $data = $this->db->fetchAssociative('SELECT * FROM ' . $this->tableName . ' WHERE id = ?', [$this->model->getId()]);

        if (!$data) {
            throw new NotFoundException("LoginAttempt with the ID " . $this->model->getId() . " doesn't exists");
        }

        $this->assignVariablesToModel($data);
    }

    /**
     * save vote
     */
    public function save(): void
    {
        $vars = get_object_vars($this->model);

        $buffer = [];

        $validColumns = $this->getValidTableColumns($this->tableName);


        if (count($vars)) {
            foreach ($vars as $k => $v) {
                if (!in_array($k, $validColumns)) {
                    continue;
                }

                $getter = "get" . ucfirst($k);

                if (!is_callable([$this->model, $getter])) {

                    continue;
                }

                $value = $this->model->$getter();

                if (is_bool($value)) {
                    $value = (int)$value;
                }

                $buffer[$k] = $value;
            }
        }

        if ($this->model->getId() !== null) {
            $this->db->update($this->tableName, $buffer, ["id" => $this->model->getId()]);
            return;
        }

        //dd($buffer);
        $this->db->insert($this->tableName, $buffer);
        $this->model->setId($this->db->lastInsertId());
    }

    /**
     * delete vote
     */
    public function delete(): void
    {
        $this->db->delete($this->tableName, ["id" => $this->model->getId()]);
    }
}
