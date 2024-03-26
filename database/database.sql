DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS departments;
DROP TABLE IF EXISTS statuses;
DROP TABLE IF EXISTS priorities;
DROP TABLE IF EXISTS tickets;
DROP TABLE IF EXISTS ticket_replies;
DROP TABLE IF EXISTS ticket_hashtags;
DROP TABLE IF EXISTS ticket_history;
DROP TABLE IF EXISTS faqs;

CREATE TABLE users (
    user_id INTEGER PRIMARY KEY,
    username TEXT NOT NULL,
    name TEXT NOT NULL,
    password TEXT NOT NULL,
    email TEXT NOT NULL,
    role TEXT NOT NULL,
    department_id INTEGER REFERENCES departments(department_id)
);

CREATE TABLE departments (
    department_id INTEGER PRIMARY KEY,
    department_name TEXT UNIQUE NOT NULL
);

CREATE TABLE statuses (
    status_id INTEGER PRIMARY KEY,
    status_name TEXT UNIQUE NOT NULL
);

CREATE TABLE priorities (
    priority_id INTEGER PRIMARY KEY,
    priority_name TEXT UNIQUE NOT NULL
);

CREATE TABLE tickets (
    ticket_id INTEGER PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(user_id),
    assigned_agent_id INTEGER REFERENCES users(user_id),
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    created_at TEXT NOT NULL,
    updated_at TEXT NOT NULL,
    department_id INTEGER REFERENCES departments(department_id),
    status_id INTEGER NOT NULL REFERENCES statuses(status_id),
    priority_id INTEGER NOT NULL REFERENCES priorities(priority_id)
);

CREATE TABLE ticket_replies (
    ticket_reply_id INTEGER PRIMARY KEY,
    ticket_id INTEGER NOT NULL REFERENCES tickets(ticket_id),
    user_id INTEGER NOT NULL REFERENCES users(user_id),
    user_role TEXT NOT NULL,
    content TEXT NOT NULL,
    created_at INTEGER NOT NULL
);

CREATE TABLE ticket_hashtags (
    hashtag_id INTEGER PRIMARY KEY, 
    ticket_id INTEGER REFERENCES tickets(ticket_id),
    hashtags TEXT NOT NULL
);

CREATE TABLE ticket_history (
    ticket_history_id INTEGER PRIMARY KEY,
    ticket_id INTEGER NOT NULL REFERENCES tickets(ticket_id),
    user_id INTEGER NOT NULL REFERENCES users(user_id),
    time_of_change TEXT NOT NULL,
    old_status_id INTEGER NOT NULL REFERENCES statuses(status_id),
    new_status_id INTEGER REFERENCES statuses(status_id),
    old_assigned_agent_id INTEGER REFERENCES users(user_id),
    new_assigned_agent_id INTEGER REFERENCES users(user_id),
    old_priority_id INTEGER NOT NULL REFERENCES priorities(priority_id),
    new_priority_id INTEGER REFERENCES priorities(priority_id),
    -- old_department_id INTEGER NOT NULL REFERENCES departments(department_id),
    old_department_id INTEGER REFERENCES departments(department_id),
    new_department_id INTEGER REFERENCES departments(department_id),
    content_change INTEGER NOT NULL,
    tags_change INTEGER NOT NULL
    

);

CREATE TABLE faqs (
    faq_id INTEGER PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL
);

INSERT INTO users (user_id, username, name, password, email, role, department_id) VALUES 
(1, 'john_doe', 'John Doe' ,'$2y$12$Usu/9ynwHwrrNQnRFhvgWekim060Q64GeKYhnJG0K.B5wJp4ZRqA6', 'john_doe@example.com', 'admin', NULL),
(2, 'jane_doe', 'Jane Doe' ,'$2y$12$Usu/9ynwHwrrNQnRFhvgWekim060Q64GeKYhnJG0K.B5wJp4ZRqA6', 'jane_doe@example.com', 'agent', NULL),
(3, 'sergio', 'Sérgio', '$2y$12$Usu/9ynwHwrrNQnRFhvgWekim060Q64GeKYhnJG0K.B5wJp4ZRqA6', 'sergio@gmail.com', 'client', NULL),
(4, 'carol', 'Carol', '$2y$12$Usu/9ynwHwrrNQnRFhvgWekim060Q64GeKYhnJG0K.B5wJp4ZRqA6', 'carol@gmail.com', 'agent', 2),
(5, 'rodrigo', 'Rodrigo', '$2y$12$Usu/9ynwHwrrNQnRFhvgWekim060Q64GeKYhnJG0K.B5wJp4ZRqA6', 'rodrigo@gmail.com', 'agent', 1);

INSERT INTO departments (department_id, department_name) VALUES 
(1, 'Sales'),
(2, 'Marketing'),
(3, 'Accounting'),
(4, 'Finance'),
(5, 'Human Resources'),
(6, 'IT'),
(7, 'Product Development');

INSERT INTO statuses (status_id, status_name) VALUES 
(1, 'Open'),
(2, 'Pending'),
(3, 'Closed');

INSERT INTO priorities (priority_id, priority_name) VALUES
 (1, 'Low'),
 (2, 'Medium'),
 (3, 'High');


INSERT INTO tickets (ticket_id, user_id, assigned_agent_id, title, content, created_at, updated_at, priority_id, department_id, status_id) VALUES 
(1, 1, 2, 'Cannot login', 'I cannot login to my account.', "2022-04-12", "2022-04-14", 1, 1, 1),
(2, 2, NULL, 'Billing discrepancy', 'My invoice doesn not add up.', "2022-03-12", "2022-04-14", 2, 5, 2),
(3, 3, 2, 'Product not shipped', 'I ordered a product, but it has not shipped yet.', "2022-04-12", "2022-05-12", 3, 7, 2),
(4, 4, 4, 'Need a refund', 'I want a refund.', "2022-03-12", "2022-06-11", 2, 6, 3),
(5, 5, 5, 'Product not working', 'I ordered a product, but it does not work.', "2022-04-12", "2022-04-16", 3, 2, 3),
(6, 5, 2, 'Need help with something', 'I need help with something.', "2022-03-12", "2022-08-12", 1, 2, 3),
(7, 4, 1, 'Difficulty signing in', 'I am unable to create new accounts.', "2022-04-12", "2022-09-01", 2, 4, 2),
(8, 3, 4, 'Long wait for shipping', 'I have made an order and have been waiting for shipping for over two months.', "2022-03-12", "2022-05-02", 3, 2, 1),
(9, 2, 4, 'Lost my package', 'I ordered a product and the shipping company said they lost my package.', "2022-04-12", "2022-07-13", 3, 2, 2),
(10, 1, 2, 'Trouble changing password', 'I change my password over and over again and it does not change.', "2022-03-12", "2022-06-12", 3, 1, 2),
(11, 5, NULL, 'Cannot contact by phone', 'I call your phone and no one picks up.', "2022-04-12", "2022-04-14", 1, 2, 3),
(12, 3, 4, 'Need your address', 'I need to send a letter to your headquarters but there is no information for the address.', "2022-03-12", "2022-04-14", 2, 3, 1),
(13, 2, 1, 'Wrong product', 'I received a product different from the one I ordered.', "2022-04-12", "2022-04-14", 1, 4, 1),
(14, 2, 1, 'Email Access Problem', 'Cannot login with my email', "2022-03-12", "2022-04-14", 2, 5, 2),
(15, 5, 2, 'Website or Network Error', 'I found bugs on your website. Who should I contact?', "2022-04-12", "2022-05-12", 3, 1, 2),
(16, 4, 1, 'Account Lockout', 'My account has been blocked.', "2022-03-12", "2022-06-11", 2, 1, 3);


INSERT INTO ticket_replies (ticket_reply_id, ticket_id, user_id, user_role, content, created_at) VALUES 
(1, 1, 2, 'agent', 'Please try resetting your password and let us know if you still have trouble logging in.', "2023-04-14"),
(2, 3, 2, 'agent', 'Please reply with your OrderNumber and we will do our best to track it', "2023-04-14"),
(3, 3, 3, 'user', 'Sure! The order is QXJ145', "2023-04-14"),
(4, 3, 2, 'agent', 'It is in fact in our store. It will be shipped tomorrow', "2023-04-15"),
(5, 5, 4, 'agent', 'Can you further explain the malfunction?', "2023-04-23"),
(6, 5, 5, 'agent', 'The batteries do not work...', "2023-04-25"),
(7, 9, 1, 'agent', 'Unfortunately, that is the responsability of the shipping company, not ours.', "2023-04-14"),
(8, 7, 1, 'agent', 'Have you tried with different emails?', "2023-04-14"),
(9, 7, 4, 'agent', 'Yes, and nothing is working...', "2023-04-14"),
(10, 10, 2, 'agent', 'Let me see what I can do... I will get back to you', "2023-04-14"),
(11, 10, 2, 'agent', 'It should be working now', "2023-04-16"),
(12, 15, 2, 'agent', 'Where?', "2023-04-14"),
(13, 15, 5, 'user', 'My mistake, it is right.', "2023-04-14");

INSERT INTO ticket_hashtags (hashtag_id, ticket_id, hashtags) VALUES 
(1, 1, '#login,#help'),
(2, 2, '#billing'),
(3, 3, '#shipping'),
(4, 4, '#refund'),
(5, 5, '#product'),
(6, 6, '#help'),
(7, 7, '#login,#help'),
(8, 8, '#billing'),
(9, 9, '#shipping'),
(10, 10, '#refund'),
(11, 11, '#product'),
(12, 12, '#help'),
(13, NULL, '#network'),
(14, NULL, '#email'),
(15, NULL, '#hardware'),
(16, NULL, '#server'),
(17, NULL, '#password'),
(18, NULL, '#performance'),
(19, NULL, '#finance'),
(22, NULL, '#price');


INSERT INTO ticket_history (ticket_history_id, ticket_id, user_id, time_of_change, old_status_id, new_status_id, old_assigned_agent_id, new_assigned_agent_id, old_priority_id, new_priority_id, old_department_id, new_department_id, content_change, tags_change) VALUES 
(1, 1, 2, "2022-04-14", 1, 1, 2, 2, 1, 2, 1, 2,0,0),
(2, 2, 1, "2022-04-14", 1, 1, 2, 2, 1, 2, 1, 2,0,0),
(3, 1, 2, "2022-04-14", 1, 1, 2, 2, 2, 1, 2, 1,0,0),
(4, 1, 2, "2022-04-14", 1, 1, 2, 2, 2, 1, 1, 2,0,0);

INSERT INTO faqs (faq_id, question, answer) VALUES 
(1, 'How do I reset my password?', 'You can reset your password by clicking the "Forgot Password" link on the login page.'),
(2, 'How do I contact support?', 'You can contact support by emailing support@example.com.'),
(3, 'What payment methods do you accept?', 'We accept Visa, Mastercard, American Express, and PayPal.'),
(4, 'Can I cancel my order?', 'You can cancel your order within 24 hours of placing it. After that, please contact customer support.'),
(5, 'What is your return policy?', 'We offer a 30-day money-back guarantee. If you are not satisfied with your purchase, please contact customer support.'),
(6, 'How do I track my order?', 'You can track your order by clicking the "Track Order" link in your account dashboard.'),
(7, 'Do you offer international shipping?', 'Yes, we offer international shipping to most countries. Shipping rates may vary.'),
(8, 'How do I update my billing information?', 'You can update your billing information in your account settings.'),
(9, 'What is your privacy policy?', 'Our privacy policy can be found on the "Privacy Policy" page.'),
(10, 'How do I leave a product review?', 'You can leave a product review by going to the product page and clicking "Write a Review."'),
(11, 'Do you offer discounts for bulk purchases?', 'Yes, we offer discounts for bulk purchases. Please contact customer support for more information.'),
(12, 'What is your shipping policy?', 'Our shipping policy can be found on the "Shipping Policy" page.'),
(13, 'What is your refund policy?', 'Our refund policy can be found on the "Refund Policy" page.'),
(14, 'How do I subscribe to your newsletter?', 'You can subscribe to our newsletter by entering your email address in the "Subscribe" box at the bottom of our website.'),
(15, 'Do you offer gift cards?', 'Yes, we offer gift cards. You can purchase them on the "Gift Cards" page.');

