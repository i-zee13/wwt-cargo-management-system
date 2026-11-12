<?php

namespace App\Mcp;

use Illuminate\Support\Facades\DB;

class DesignationAuthorizer
{
    public function __construct(private ToolCatalog $catalog) {}

    public function allows(McpActor $actor, string $tool): bool
    {
        $rule = $this->catalog->rule($tool);
        if ($rule === null) {
            return false;
        }

        if ($actor->isClient()) {
            return $rule['client'] !== 'deny';
        }

        return $this->staffCan($actor, $rule['staff_slug']);
    }

    public function staffCan(McpActor $actor, string $slug): bool
    {
        $exists = DB::table('controllers')->where('controller', $slug)->exists();
        if (! $exists) {
            return false;
        }

        if ($actor->super) {
            return true;
        }

        if ($actor->designationId === null || $actor->designationId === '') {
            return false;
        }

        return DB::table('controller_designation_assignments')
            ->where('controller_name', $slug)
            ->where('designation_id', $actor->designationId)
            ->exists();
    }
}
