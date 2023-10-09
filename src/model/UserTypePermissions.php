<?php

namespace Mini\model;

use Mini\core\Model;

final class UserTypePermissions extends Model
{
    const TABLE = 'user_type_permissions';

    function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function findFullRouteByIdUserType(int $idUserType): array
    {
        $sql = "SELECT 
                    uta.*,
                    if (uta.sub_route and uta.sub_route is not null, concat(uta.route, '/', uta.sub_route), concat(uta.route, '/')) as route_full
                FROM
                    $this->table uta
                WHERE
                    uta.id_user_type = $idUserType";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
