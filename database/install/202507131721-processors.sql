CREATE TABLE IF NOT EXISTS processors (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4 (),
    name VARCHAR(255) NOT NULL
);

INSERT INTO processors (id, name) VALUES
    ('00000000-0000-0000-0000-000000000001', 'Processor Default'),
    ('00000000-0000-0000-0000-000000000002', 'Processor Fallback');
