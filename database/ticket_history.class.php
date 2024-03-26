<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/../database/users.class.php');
    require_once(__DIR__ . '/../database/status.class.php');
    require_once(__DIR__ . '/../database/ticket.class.php');

    class Ticket_History{
        public int $ticket_history_id;
        public int $ticket_id;
        public string $user;
        public DateTime $time_of_change;
        public string $old_status;
        public string $new_status;
        public string $old_assigned_agent;
        public string $new_assigned_agent;
        public string $old_priority;
        public string $new_priority;
        public string $old_department;
        public string $new_department;
        public int $content_change;
        public int $tags_change;

        public function __construct(int $ticket_history_id, int $ticket_id, string $user, string $time_of_change, string $old_status, string $new_status, string $old_assigned_agent, string $new_assigned_agent, string $old_priority, string $new_priority, string $old_department, string $new_department, int $content_change, int $tags_change) {
            $this->ticket_history_id = $ticket_history_id;
            $this->ticket_id = $ticket_id;
            $this->user = $user;
            $this->time_of_change = new DateTime($time_of_change);
            $this->old_status = $old_status;
            $this->new_status = $new_status;
            $this->old_assigned_agent = $old_assigned_agent;
            $this->new_assigned_agent = $new_assigned_agent;
            $this->old_priority = $old_priority;
            $this->new_priority = $new_priority;
            $this->old_department = $old_department;
            $this->new_department = $new_department;
            $this->content_change = $content_change;
            $this->tags_change = $tags_change;
        }
    

        public static function getTicketHistory(PDO $db) : array {
            $query = $db->query('SELECT * FROM ticket_history');
            $query->execute();

            $statuses = array();
            while($ticket_history = $query->fetch(PDO::FETCH_ASSOC)){
                $arr_ticket_history[] = Ticket_History::createTicketHistoryObject($db,$ticket_history);
            }
            return $arr_ticket_history;
        }
    

    private static function createTicketHistoryObject(PDO $db, array $ticket_history) : Ticket_History{    
        return new Ticket_History(
            (int)$ticket_history['ticket_history_id'],
            (int)$ticket_history['ticket_id'],
            User::getUsernameByID($db,(int)($ticket_history['user_id'])),
            $ticket_history['time_of_change'],
            Ticket::getTicketStatusByID($db,(int)($ticket_history['old_status_id'])),
            Ticket::getTicketStatusByID($db,(int)($ticket_history['new_status_id'])),
            User::getNameByID($db,(int)($ticket_history['old_assigned_agent_id'])),
            User::getNameByID($db,(int)($ticket_history['new_assigned_agent_id'])),
            Ticket::getPriorityByID($db,(int)($ticket_history['old_priority_id'])),
            Ticket::getPriorityByID($db,(int)($ticket_history['new_priority_id'])),
            Ticket::getDepartmentByID($db,(int)($ticket_history['old_department_id'])),
            Ticket::getDepartmentByID($db,(int)($ticket_history['new_department_id'])),
            (int)$ticket_history['content_change'],
            (int)$ticket_history['tags_change']
        );
    }

    public static function getTicketHistoryByID(PDO $db,int $id): array{
        $query = $db->prepare('SELECT * FROM ticket_history WHERE ticket_id = ?');
        $query->execute(array($id));
        $arr_ticket_history = array();
        while($ticket_history = $query->fetch(PDO::FETCH_ASSOC)){
            $arr_ticket_history[] = Ticket_History::createTicketHistoryObject($db,$ticket_history);
        }

        return $arr_ticket_history;
    }
}
?>
