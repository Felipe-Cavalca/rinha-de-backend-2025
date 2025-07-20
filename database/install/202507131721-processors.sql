CREATE TABLE IF NOT EXISTS processors (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4 (),
    name VARCHAR(255) NOT NULL,
    tax NUMERIC(10,2) DEFAULT 0.0,
    url VARCHAR(255) DEFAULT NULL
);

INSERT INTO processors (id, name, tax, url) VALUES
    ('00000000-0000-0000-0000-000000000001', 'Processor Default', 0.05, 'http://payment-processor-default:8001'),
    ('00000000-0000-0000-0000-000000000002', 'Processor Fallback', 0.15, 'http://payment-processor-fallback:8002');
