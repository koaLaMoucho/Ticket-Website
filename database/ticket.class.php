<?php

declare(strict_types=1);
require_once(__DIR__ . '/../database/users.class.php');
require_once(__DIR__ . '/../database/hashtag.class.php');


class Ticket
{
    public int $id;
    public string $email;
    public string $title;
    public string $description;
    public string $status;
    public DateTime $created_at;
    public string $user;
    public ?string $assigned_to;
    public ?string $department;
    public string $priority;
    public ?array $tags;
    public DateTime $last_update;
    public int $user_id;

    public function __construct(int $id, string $email, string $title, string $description, string $status,  string $created_at, string $user, ?string $assigned_to,  ?string $department, string $priority, ?array $tags, string $last_update, int $user_id)
    {
        $this->id = $id;
        $this->email = $email;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->created_at = new DateTime($created_at);
        $this->user = $user;
        $this->assigned_to = $assigned_to;
        $this->department = $department;
        $this->priority = $priority;
        $this->tags = $tags;
        $this->last_update = new DateTime($last_update);
        $this->user_id = $user_id;
    }

    static function getTickets(PDO $db): array
    {
        $query = $db->query('SELECT * FROM tickets t ORDER BY t.updated_at DESC');
        $tickets = [];
        while ($ticket = $query->fetch()) {
            $tickets[] = Ticket::createTicketObject($db, $ticket);
        }

        return $tickets;
    }

    static function getTicketsByID(PDO $db, int $user_id): array
    {
        $query = $db->query('SELECT * FROM tickets t WHERE user_id = ?');
        $query->execute(array($user_id));

        $tickets = [];
        while ($ticket = $query->fetch()) {
            $tickets[] = Ticket::createTicketObject($db, $ticket);
        }

        return $tickets;
    }

    static function getTicket(PDO $db, int $id): Ticket
    {
        $query = $db->prepare('SELECT * FROM tickets WHERE ticket_id = ?');
        $query->execute(array($id));
        $ticket = $query->fetch(PDO::FETCH_ASSOC);

        return Ticket::createTicketObject($db, $ticket);
    }

    private static function createTicketObject(PDO $db, array $ticket): Ticket
    {
        return new Ticket(
            (int)$ticket['ticket_id'],
            User::getEmailByID($db, (int)($ticket['user_id'])),
            $ticket['title'],
            $ticket['content'],
            Ticket::getTicketStatusByID($db, intval(($ticket['status_id']))),
            $ticket['created_at'],
            User::getNameByID($db, (int)($ticket['user_id'])),
            User::getNameByID($db, (int)($ticket['assigned_agent_id'])),
            Ticket::getDepartmentByID($db, (int)($ticket['department_id'])),
            Ticket::getPriorityByID($db, (int)($ticket['priority_id'])),
            Tag::getTagsByTicketID($db, (int)($ticket['ticket_id'])),
            $ticket['updated_at'],
            intval($ticket['user_id'])
        );
    }

    public static function searchTickets(PDO $db, string $search): array
    {
        $query = $db->prepare(
            'SELECT t.ticket_id, t.user_id, t.title, t.content, t.status_id, t.created_at, t.assigned_agent_id, t.priority_id, t.updated_at, t.department_id
        FROM tickets t 
        join users u on t.user_id = u.user_id
        join users agent on t.assigned_agent_id = agent.user_id
        join departments d on t.department_id = d.department_id
        join statuses s on t.status_id = s.status_id
        join ticket_hashtags th on t.ticket_id = th.ticket_id

        WHERE t.title LIKE ? 
        OR t.content LIKE ?
        OR u.name LIKE ?
        OR agent.name LIKE ?
        OR u.email LIKE ?
        OR d.department_name LIKE ?
        OR s.status_name LIKE ?
        OR th.hashtags LIKE ?
        
        ORDER BY t.updated_at DESC'
        );
        $query->execute(array("%$search%", "%$search%", "%$search%", "%$search%", "%$search%", "%$search%", "%$search%", "%$search%"));
        $tickets = [];
        while ($ticket = $query->fetch(PDO::FETCH_ASSOC)) {
            $tickets[] = Ticket::createTicketObject($db, $ticket);
        }

        return $tickets;
    }

    public static function filterTickets(PDO $db, int $status_id, int $assignee_id, int $department_id, int $requester_id, string $tags, string $order = 'ASC')
    {

        $query = 'SELECT t.ticket_id, t.user_id, t.title, t.content, t.status_id, t.created_at, t.assigned_agent_id, t.priority_id, t.updated_at, t.department_id 
        FROM tickets t 
        
        join ticket_hashtags th on t.ticket_id = th.ticket_id';


        $filters = array();

        if ($status_id != -1)
            array_push($filters, "t.status_id = '$status_id'");

        if ($assignee_id != -1)
            array_push($filters, "t.assigned_agent_id = '$assignee_id'");

        if ($department_id != -1)
            array_push($filters, "t.department_id = '$department_id'");

        if ($requester_id != -1)
            array_push($filters, "t.user_id = '$requester_id'");

        if ($tags != 'all')
            array_push($filters, "th.hashtags LIKE '%$tags%'");

        if (count($filters) > 0) {
            $query .= ' WHERE ' . implode(' AND ', $filters);
        }

        $query .= ' ORDER BY t.updated_at ' . $order;


        $query = $db->prepare($query);
        $query->execute();

        $tickets = [];
        while ($ticket = $query->fetch(PDO::FETCH_ASSOC)) {
            $ticket =
                $tickets[] = Ticket::createTicketObject($db, $ticket);
        }

        return $tickets;
    }

    public static function getTicketStatusByID(PDO $db, int $statusID)
    {
        $query = $db->prepare('SELECT * FROM statuses WHERE status_id = ?');
        $query->execute(array($statusID));
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row['status_name'];
    }

    public static function getDepartmentByID(PDO $db, int $departmentID)
    {
        $query = $db->prepare('SELECT * FROM departments WHERE department_id = ?');
        $query->execute(array($departmentID));
        $row = $query->fetch(PDO::FETCH_ASSOC);

        return $row['department_name'] == null ? "Unassigned" : $row['department_name'];
    }

    public static function getPriorityByID(PDO $db, int $priorityID)
    {
        $query = $db->prepare('SELECT * FROM priorities WHERE priority_id = ?');
        $query->execute(array($priorityID));
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row['priority_name'];
    }

    public static function addTicket(PDO $db, int $user_id, string $subject, string  $description, int $department_id, DateTime $created_at, string $tags)
    {
        
        if ($department_id === 0){
            $stmt = $db->prepare('INSERT INTO tickets (ticket_id, user_id, assigned_agent_id, title, content, created_at, updated_at, department_id, status_id, priority_id) 
                                                VALUES (NULL     , ?      , 0                , ?    , ?      , ?         , ?         , NULL , 1        , 1)');

            $stmt->execute(array($user_id, $subject, $description, $created_at->format('Y-m-d'), $created_at->format('Y-m-d')));
        }
        else{
            $stmt = $db->prepare('INSERT INTO tickets (ticket_id, user_id, assigned_agent_id, title, content, created_at, updated_at, department_id, status_id, priority_id) 
                                           VALUES (NULL     , ?      , 0                , ?    , ?      , ?         , ?         , ?            , 1        , 1)');

            $stmt->execute(array($user_id, $subject, $description, $created_at->format('Y-m-d'), $created_at->format('Y-m-d'), $department_id));
        }

        $stmt = $db->prepare('SELECT ticket_id FROM tickets WHERE user_id = ? AND title = ? AND content = ? AND created_at = ? AND updated_at = ?');
        $stmt->execute(array($user_id, $subject, $description, $created_at->format('Y-m-d'), $created_at->format('Y-m-d')));


        Tag::addTag($db, (int) $stmt->fetch(PDO::FETCH_ASSOC)['ticket_id'], $tags);
    }

    public static function editTicket(PDO $db, $ticket_id, $user_id, $newStatus, $newPriority, $newDepartment, $newAssignedTo, $updated_at)
    {
        $stmt = $db->prepare('SELECT * FROM tickets WHERE ticket_id = :id');
        $stmt->bindValue(':id', $ticket_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $oldStatus = $row['status_id'];
        $oldPriority = $row['priority_id'];
        $oldDepartment = $row['department_id'];
        $oldAssignedTo = $row['assigned_agent_id'];


        if ($oldStatus == $newStatus && $oldPriority == $newPriority && $oldDepartment == $newDepartment && $oldAssignedTo == $newAssignedTo) {
            return; // Do nothing if values are the same
        }

        // Update the ticket
        $stmt = $db->prepare('UPDATE tickets SET updated_at = :update,status_id = :status, priority_id = :priority, department_id = :department, assigned_agent_id = :assigned_to WHERE ticket_id = :id');
        $stmt->bindValue(':update', $updated_at->format('Y-m-d H:i:s'));
        $stmt->bindValue(':status', $newStatus);
        $stmt->bindValue(':priority', $newPriority);
        $stmt->bindValue(':department', $newDepartment);
        $stmt->bindValue(':assigned_to', $newAssignedTo);
        $stmt->bindValue(':id', $ticket_id);
        $stmt->execute();

        $stmt = $db->prepare('INSERT INTO ticket_history (ticket_id, user_id, time_of_change, old_status_id, new_status_id, old_assigned_agent_id, new_assigned_agent_id, old_priority_id, new_priority_id, old_department_id, new_department_id, content_change, tags_change) VALUES (:ticket_id, :user_id, :time_of_change, :old_status_id, :new_status_id, :old_assigned_agent_id, :new_assigned_agent_id, :old_priority_id, :new_priority_id, :old_department_id, :new_department_id, :content_change,:tags_change)');
        $stmt->bindValue(':ticket_id', $ticket_id);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':time_of_change', $updated_at->format('Y-m-d H:i:s'));
        $stmt->bindValue(':old_status_id', $oldStatus);
        $stmt->bindValue(':new_status_id', $newStatus);
        $stmt->bindValue(':old_assigned_agent_id', $oldAssignedTo);
        $stmt->bindValue(':new_assigned_agent_id', $newAssignedTo);
        $stmt->bindValue(':old_priority_id', $oldPriority);
        $stmt->bindValue(':new_priority_id', $newPriority);
        $stmt->bindValue(':old_department_id', $oldDepartment);
        $stmt->bindValue(':new_department_id', $newDepartment);
        $stmt->bindValue(':content_change', 0);
        $stmt->bindValue(':tags_change', 0);
        $stmt->execute();
    }
    
    public static function editTicketContent($db, $ticket_id, $new_content, $updated_at, $user_id)
    {
        $stmt = $db->prepare('SELECT * FROM tickets WHERE ticket_id = :id');
        $stmt->bindValue(':id', $ticket_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $oldStatus = $row['status_id'];
        $oldPriority = $row['priority_id'];
        $oldDepartment = $row['department_id'];
        $oldAssignedTo = $row['assigned_agent_id'];

        $stmt = $db->prepare('UPDATE tickets SET updated_at = :update, content = :newContent WHERE ticket_id = :id');
        $stmt->bindValue(':update', $updated_at->format('Y-m-d H:i:s'));

        $stmt->bindValue(':newContent', $new_content);

        $stmt->bindValue(':id', $ticket_id);
        $stmt->execute();

        $stmt = $db->prepare('INSERT INTO ticket_history (ticket_id, user_id, time_of_change, old_status_id, new_status_id, old_assigned_agent_id, new_assigned_agent_id, old_priority_id, new_priority_id, old_department_id, new_department_id, content_change, tags_change) VALUES (:ticket_id, :user_id, :time_of_change, :old_status_id, :new_status_id, :old_assigned_agent_id, :new_assigned_agent_id, :old_priority_id, :new_priority_id, :old_department_id, :new_department_id, :content_change, :tags_change)');
        $stmt->bindValue(':ticket_id', $ticket_id);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':time_of_change', $updated_at->format('Y-m-d H:i:s'));
        $stmt->bindValue(':old_status_id', $oldStatus);
        $stmt->bindValue(':new_status_id', $oldStatus);
        $stmt->bindValue(':old_assigned_agent_id', $oldAssignedTo);
        $stmt->bindValue(':new_assigned_agent_id', $oldAssignedTo);
        $stmt->bindValue(':old_priority_id', $oldPriority);
        $stmt->bindValue(':new_priority_id', $oldPriority);
        $stmt->bindValue(':old_department_id', $oldDepartment);
        $stmt->bindValue(':new_department_id', $oldDepartment);
        $stmt->bindValue(':content_change', 1);
        $stmt->bindValue(':tags_change', 0);
        $stmt->execute();
    }

    public static function editTicketStatusClosed(PDO $db, $ticket_id, $user_id, $updated_at)
    {
        $stmt = $db->prepare('SELECT * FROM tickets WHERE ticket_id = :id');
        $stmt->bindValue(':id', $ticket_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $oldStatus = $row['status_id'];
        $oldPriority = $row['priority_id'];
        $oldDepartment = $row['department_id'];
        $oldAssignedTo = $row['assigned_agent_id'];
        $newStatus = (int)"3";



        // Update the ticket
        $stmt = $db->prepare('UPDATE tickets SET updated_at = :update,status_id = :status WHERE ticket_id = :id');
        $stmt->bindValue(':update', $updated_at->format('Y-m-d H:i:s'));
        $stmt->bindValue(':status', $newStatus);

        $stmt->bindValue(':id', $ticket_id);
        $stmt->execute();

        $stmt = $db->prepare('INSERT INTO ticket_history (ticket_id, user_id, time_of_change, old_status_id, new_status_id, old_assigned_agent_id, new_assigned_agent_id, old_priority_id, new_priority_id, old_department_id, new_department_id, content_change, tags_change) VALUES (:ticket_id, :user_id, :time_of_change, :old_status_id, :new_status_id, :old_assigned_agent_id, :new_assigned_agent_id, :old_priority_id, :new_priority_id, :old_department_id, :new_department_id, :content_change,:tags_change)');
        $stmt->bindValue(':ticket_id', $ticket_id);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':time_of_change', $updated_at->format('Y-m-d H:i:s'));
        $stmt->bindValue(':old_status_id', $oldStatus);
        $stmt->bindValue(':new_status_id', $newStatus);
        $stmt->bindValue(':old_assigned_agent_id', $oldAssignedTo);
        $stmt->bindValue(':new_assigned_agent_id', $oldAssignedTo);
        $stmt->bindValue(':old_priority_id', $oldPriority);
        $stmt->bindValue(':new_priority_id', $oldPriority);
        $stmt->bindValue(':old_department_id', $oldDepartment);
        $stmt->bindValue(':new_department_id', $oldDepartment);
        $stmt->bindValue(':content_change', 0);
        $stmt->bindValue(':tags_change', 0);
        $stmt->execute();
    }

    public static function deleteTicket($db, $ticket_id)
    {
        $stmt = $db->prepare('DELETE FROM tickets WHERE ticket_id = :id');

        $stmt->bindValue(':id', $ticket_id);
        $stmt->execute();
    }
}
