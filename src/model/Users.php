<?php

namespace Mini\model;

use Mini\core\DTModel;
use Mini\core\ModelInsertReturn;
use Mini\core\ModelReturn;

final class Users extends DTModel
{
    const TABLE = 'users';
    const UPLOADS_PATH = "/uploads/" . self::TABLE . "/";
    const UPLOADS_DIRECTORY = ROOT . 'public' . self::UPLOADS_PATH;

    function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /** Busca os contatos do chat seguindo as regras de negócio pré-estabelecidas */
    public function findByIdWithImage(int|string|null $idUser): object|false
    {
        if (!$idUser) return false;
        $UserTypes = UserTypes::TABLE;
        $Uploads = Uploads::TABLE;

        $sql = "SELECT
                    u.*,
                    ut.name as user_type,
                    case 
                        when u.id_upload_cover and u.id_upload_cover is not null then concat('" . '.' . Users::UPLOADS_PATH . "', u.id, '/', u2.file)
                        else concat('./images/user-no-cover.jpg')
                    end as image
                from
                    {$this->table} u
                left join
                    $UserTypes ut
                on
                    ut.id = u.id_user_type
                left join
                    $Uploads u2
                on
                    u2.id = u.id_upload_cover
                where
                    u.id = $idUser";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findWithCover(int $idUser): object|false
    {
        $Uploads = Uploads::TABLE;

        $sql = "SELECT
                    u.*,
                    case 
                        when u.id_upload_cover and u.id_upload_cover is not null then concat('" . '.' . Users::UPLOADS_PATH . "', u.id, '/', u2.file)
                        else concat('./images/user-no-cover.jpg')
                    end as cover
                from
                    {$this->table} u
                left join
                    $Uploads u2
                on
                    u2.id = u.id_upload_cover
                where
                    u.id = $idUser";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }

    private function saveFile(array $file, int $idUser): string
    {
        $path = self::UPLOADS_DIRECTORY . "$idUser";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . ".$extension";
        $destination = "$path/$fileName";
        move_uploaded_file($file['tmp_name'], $destination);
        return $fileName;
    }

    public function deleteFile(int $idUser, int $idUpload): ModelReturn
    {
        $coverUploaded = (new Uploads)->findById($idUpload);
        $result = (new Uploads)->delete($idUpload);

        $path = self::UPLOADS_DIRECTORY . "$idUser/$coverUploaded->file";
        @unlink($path);

        return $result;
    }

    public function insertUpload(array $file, int $idUser, $description = ''): ModelInsertReturn
    {
        $fileName = $this->saveFile($file, $idUser);

        return (new Uploads)->insert([
            "name"       => $file['name'],
            "file"    => $fileName,
            "created_by" => $_SESSION['user']->id,
            "description"  => $description
        ]);
    }


    public function updateSessionUserData(int $idUser): bool
    {
        $Uploads = Uploads::TABLE;

        $sql = "SELECT
                    u.*,
                    case 
                        when u.id_upload_cover and u.id_upload_cover is not null then concat('" . '.' . Users::UPLOADS_PATH . "', u.id, '/', u2.file)
                        else concat('./images/user-no-cover.jpg')
                    end as url_cover
                from
                    {$this->table} u
                left join
                    $Uploads u2
                on
                    u2.id = u.id_upload_cover
                where
                    u.id = $idUser";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $userForSession = $stmt->fetch();

        unset($userForSession->password);
        $_SESSION['user'] = $userForSession;

        return !!$_SESSION['user'];
    }
}
