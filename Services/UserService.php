<?php

use Illuminate\Support\Collection;

class UserService
{
    private DatabaseConnector $dbContext;

    public function __construct()
    {
        $this->dbContext = new DatabaseConnector();
    }

    /**
     * get all users
     * return Collection of users
     * @return Illuminate\Support\Collection
     */
    public function getUsers(): Collection
    {
        return $this->dbContext->getDbc()::table("users")->select("e_mail")->get();
    }




    /**
     * get user by id
     * return selected user or NULL
     * @param int $id
     * @return stdClass|null
     */
    public function getUserById(int $id): ?stdClass

    {
        return $this->dbContext->getDbc()::table('users')->where("ID", $id)->select("e_mail","user_password")->first();
    }

    /**
     * get user by email
     * return selected user or NULL
     * @param string $email
     * @return stdClass|null
     */
    private function getUserByEmail(string $email): ?stdClass
    {
        return $this->dbContext->getDbc()::table('users')->where("e_mail", $email)->select("e_mail","user_password")->first();
    }

    /**
     * update user data
     * return true if user inserted
     * false if user not inserted
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function insertUser(string $email, string $password): bool
    {
        $selectedUser = $this->getUserByEmail($email);
        $isInserted = false;
        $hashedPassword=sha1($password);
        if (is_null($selectedUser)) {
            $newUser = ["e_mail" => $email, "user_password" => $hashedPassword];
            $this->dbContext->getDbc()::table('users')->insert($newUser);
            $isInserted = true;
        }

        return $isInserted;
    }

    /**
     * update user data
     * return affected rows number
     * @param int $id
     * @param string $email
     * @param string $password
     * @return int
     */
    public function updateUser(int $id, string $email, string $password): int
    {
        return $this->dbContext->getDbc()::table('users')
            ->where('ID', $id)
            ->update(['e_mail' => $email, 'password', $password]);
    }

    /**
     * delete user from database.
     * return affected rows number
     * @param int $id
     * @return int
     */
    public function removeUser(int $id): int
    {
        return $this->dbContext->getDbc()::table('users')->where('ID', $id)->delete();
    }
    /**
     * update user data
     * return affected rows number
     *
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function is_authUser(string $email,string $password) : bool
    {
        $userinfo=$this->getUserByEmail($email);
        var_dump($userinfo);
        if(is_null($userinfo))
        {
                return false;
        }else{
            $regPassword=$userinfo->user_password;
            $hashedPassword=sha1($password);
            if(strcmp($regPassword,$hashedPassword)!=0)
            {
                return false;
            }else
            {
                return true;
            }
        }

    }

    // remember me 
    // generate token 
}