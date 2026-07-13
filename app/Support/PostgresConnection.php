<?php

declare(strict_types=1);

namespace App\Support;

use DateTimeInterface;
use Illuminate\Database\PostgresConnection as BasePostgresConnection;

/**
 * Laravel's base Connection::prepareBindings() unconditionally casts PHP
 * booleans to integers (0/1) for binding, on every driver. That's harmless
 * with a real server-side PREPARE (Postgres already knows the parameter's
 * expected type from the query plan, so it accepts 1 as boolean-compatible),
 * but PDO::ATTR_EMULATE_PREPARES — required for Supabase's transaction-mode
 * pooler, see config/database.php — pastes the bound value into the SQL text
 * as a literal. A literal integer 1 compared against a boolean column has no
 * implicit cast in Postgres ("operator does not exist: boolean = integer"),
 * whereas the quoted strings 'true'/'false' do.
 */
class PostgresConnection extends BasePostgresConnection
{
    /**
     * @param  array<array-key, mixed>  $bindings
     * @return array<array-key, mixed>
     */
    public function prepareBindings(array $bindings): array
    {
        $grammar = $this->getQueryGrammar();

        foreach ($bindings as $key => $value) {
            if ($value instanceof DateTimeInterface) {
                $bindings[$key] = $value->format($grammar->getDateFormat());
            } elseif (is_bool($value)) {
                $bindings[$key] = $value ? 'true' : 'false';
            }
        }

        return $bindings;
    }
}
