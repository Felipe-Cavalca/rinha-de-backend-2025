CREATE TABLE IF NOT EXISTS payments (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4 (),
    amount DECIMAL(10, 2) NOT NULL,
    processed_by UUID REFERENCES processors (id) ON DELETE RESTRICT DEFAULT NULL,
    processed_at TIMESTAMPTZ DEFAULT NULL
);
