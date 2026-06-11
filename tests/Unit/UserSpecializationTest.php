<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserSpecializationTest extends TestCase
{
    public function test_specialization_list_handles_json_arrays_and_comma_separated_strings(): void
    {
        $jsonUser = new User(['specialization' => '["Cardiology","Medicine"]']);
        $csvUser = new User(['specialization' => 'Cardiology, Medicine,']);
        $blankUser = new User(['specialization' => null]);

        $this->assertSame(['Cardiology', 'Medicine'], $jsonUser->specializationList());
        $this->assertSame(['Cardiology', 'Medicine'], $csvUser->specializationList());
        $this->assertSame([], $blankUser->specializationList());
    }

    public function test_specialization_label_uses_fallback_when_empty(): void
    {
        $this->assertSame('Cardiology, Medicine', (new User([
            'specialization' => 'Cardiology,Medicine',
        ]))->specializationLabel());

        $this->assertSame('General Practice', (new User())->specializationLabel());
        $this->assertSame('Family Medicine', (new User())->specializationLabel('Family Medicine'));
    }
}
