INSERT INTO qdb_quotes (score, votes, status, quote) VALUES
    (350, 450, 1, "quote one"),
    (1, 10, 1, "quote two"),
    (449, 450, 1, "quote three"),
    (300, 1000, 1, "quote four"),
    (350, 450, 1, "quote five");

INSERT INTO qdb_quotes (id, score, votes, status, quote) VALUES
    (1337, 1000, 1200, 1, "quote leet"),
    (1338, 1000, 1200, 0, "quote unleet");

INSERT INTO qdb_votes (qid, ip, time, value) VALUES 
    (1, '192.168.1.1', NOW(), 1),
    (2, '192.168.1.1', NOW(), 0),
    (1, '192.168.1.2', NOW(), 1),
    (2, '192.168.1.2', NOW(), 1);

