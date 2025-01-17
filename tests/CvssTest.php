<?php

declare(strict_types=1);

namespace Rootshell\Cvss\Test;

use PHPUnit\Framework\TestCase;
use Rootshell\Cvss\Cvss;
use Rootshell\Cvss\Exceptions\CvssException;

class CvssTest extends TestCase
{

    /**
     * @dataProvider vectorProvider
     */
    public function testGenerateScores(string $vector, float $baseScore, float $temporalScore, float $environmentScore): void
    {
        $result = Cvss::generateScores($vector);

        self::assertEquals($baseScore, $result->baseScore);
        self::assertEquals($temporalScore, $result->temporalScore);
        self::assertEquals($environmentScore, $result->environmentalScore);
    }

    public static function vectorProvider(): array
    {
        return [
            ['CVSS:4.0/AV:L/AC:L/AT:P/PR:L/UI:N/VC:H/VI:H/VA:H/SC:N/SI:N/SA:N', 7.3, 7.3, 7.3],
            ['CVSS:4.0/AV:N/AC:L/AT:P/PR:N/UI:P/VC:H/VI:H/VA:H/SC:N/SI:N/SA:N', 7.7, 7.7, 7.7],
            ['CVSS:4.0/AV:N/AC:L/AT:P/PR:N/UI:P/VC:H/VI:H/VA:H/SC:N/SI:N/SA:N/E:U', 5.2, 5.2, 5.2],
            ['CVSS:4.0/AV:N/AC:L/AT:P/PR:N/UI:N/VC:H/VI:L/VA:L/SC:N/SI:N/SA:N', 8.3, 8.3, 8.3],
            ['CVSS:4.0/AV:N/AC:L/AT:P/PR:N/UI:N/VC:H/VI:L/VA:L/SC:N/SI:N/SA:N/CR:H/IR:L/AR:L/MAV:N/MAC:H/MVC:H/MVI:L/MVA:L', 8.1, 8.1, 8.1],
            ['CVSS:4.0/AV:L/AC:L/AT:N/PR:N/UI:A/VC:L/VI:N/VA:N/SC:N/SI:N/SA:N', 4.6, 4.6, 4.6],
            ['CVSS:4.0/AV:N/AC:L/AT:N/PR:N/UI:A/VC:N/VI:N/VA:N/SC:L/SI:L/SA:N', 5.1, 5.1, 5.1],
            ['CVSS:4.0/AV:N/AC:L/AT:N/PR:N/UI:N/VC:N/VI:N/VA:N/SC:L/SI:L/SA:N', 6.9, 6.9, 6.9],
            ['CVSS:4.0/AV:L/AC:L/AT:N/PR:H/UI:N/VC:N/VI:N/VA:N/SC:H/SI:N/SA:N', 5.9, 5.9, 5.9],
            ['CVSS:4.0/AV:L/AC:L/AT:N/PR:N/UI:N/VC:H/VI:H/VA:H/SC:H/SI:H/SA:H', 9.4, 9.4, 9.4],
            ['CVSS:4.0/AV:P/AC:L/AT:N/PR:N/UI:N/VC:H/VI:H/VA:H/SC:N/SI:H/SA:N/S:P/V:D', 8.3, 8.3, 8.3],
            ['CVSS:4.0/AV:N/AC:L/AT:N/PR:N/UI:N/VC:H/VI:N/VA:N/SC:N/SI:N/SA:N/E:A', 8.7, 8.7, 8.7],
            ['CVSS:4.0/AV:N/AC:L/AT:N/PR:N/UI:N/VC:H/VI:H/VA:H/SC:H/SI:H/SA:H/E:A', 10.0, 10.0, 10.0],
            ['CVSS:4.0/AV:N/AC:L/AT:N/PR:N/UI:N/VC:H/VI:H/VA:H/SC:N/SI:N/SA:N/E:A', 9.3, 9.3, 9.3],
            ['CVSS:4.0/AV:A/AC:L/AT:N/PR:N/UI:N/VC:N/VI:L/VA:N/SC:H/SI:N/SA:H', 6.4, 6.4, 6.4],
            ['CVSS:4.0/AV:N/AC:L/AT:P/PR:N/UI:P/VC:H/VI:H/VA:H/SC:N/SI:N/SA:N/E:P', 6.8, 6.8, 6.8],
            ['CVSS:4.0/AV:A/AC:L/AT:N/PR:N/UI:N/VC:H/VI:H/VA:N/SC:N/SI:N/SA:N/MSI:S/S:P', 9.7, 9.7, 9.7],
            ['CVSS:4.0/AV:A/AC:H/AT:P/PR:L/UI:P/VC:L/VI:H/VA:L/SC:L/SI:H/SA:L/E:P/CR:M/IR:L/AR:M/MAV:N/MAC:H/MAT:P/MPR:L/MUI:P/MVC:L/MVI:H/MVA:L/MSC:H/MSI:L/MSA:L/S:N/AU:N/R:U/V:D/RE:L/U:Green', 4.9, 4.9, 4.9],

            ['CVSS:3.1/AV:A/AC:L/PR:L/UI:N/S:U/C:H/I:H/A:H', 8.0, 8.0, 8.0],
            ['CVSS:3.1/AV:P/AC:H/PR:H/UI:R/S:U/C:N/I:N/A:N/RL:U', 0.0, 0.0, 0.0],
            ['CVSS:3.1/AV:P/AC:H/PR:L/UI:R/S:U/C:L/I:L/A:H/E:H/RL:U/RC:U', 5.0, 4.6, 4.6],
            ['CVSS:3.1/AV:P/AC:H/PR:N/UI:R/S:C/C:L/I:H/A:N', 5.6, 5.6, 5.6],
            ['CVSS:3.1/AV:P/AC:H/PR:N/UI:R/S:C/C:L/I:H/A:N/E:P', 5.6, 5.3, 5.3],
            ['CVSS:3.1/AV:P/AC:H/PR:N/UI:R/S:C/C:L/I:H/A:N/E:P/RL:O', 5.6, 5.1, 5.1],
            ['CVSS:3.1/AV:P/AC:H/PR:N/UI:R/S:C/C:L/I:H/A:N/E:P/RL:O/RC:U', 5.6, 4.7, 4.7],
            ['CVSS:3.1/AV:P/AC:H/PR:H/UI:R/S:U/C:N/I:N/A:N/E:P/RL:T/RC:R/CR:L/IR:L/AR:L/MAV:L/MAC:L/MPR:H/MUI:R/MS:U/MC:H/MI:H/MA:H', 0.0, 0.0, 4.1],
            ['CVSS:3.1/AV:N/AC:H/PR:L/UI:R/S:C/C:L/I:L/A:L/E:U/RL:O/RC:R/CR:M/IR:L/AR:H/MAV:P/MAC:H/MPR:L/MUI:R/MS:U/MC:L/MI:H/MA:H', 5.5, 4.6, 5.2],
            ['CVSS:3.1/AV:A/AC:L/PR:L/UI:N/S:U/C:H/I:H/A:H/CR:M/IR:M/AR:M/MAV:A/MAC:H/MUI:R/MS:U/MC:L/MI:L/MA:L', 8.0, 8.0, 4.3],
            ['CVSS:3.1/AV:N/AC:L/PR:N/UI:N/S:C/C:H/I:H/A:H/E:U/RL:T/RC:U/CR:L/IR:L/AR:H/MAV:P/MAC:H/MPR:H/MUI:R/MS:C/MC:L/MI:H/MA:H', 10.0, 8.1, 5.6],
            ['CVSS:3.1/AV:N/AC:L/PR:N/UI:N/S:C/C:H/I:H/A:H/E:U/RL:T/RC:U/CR:L/IR:L/AR:H/MAV:P/MAC:H/MPR:H/MUI:R/MS:C/MC:H/MI:H/MA:H', 10.0, 8.1, 5.6],

            ['CVSS:3.0/AV:A/AC:L/PR:L/UI:N/S:U/C:H/I:H/A:H', 8.0, 8.0, 8.0],
            ['CVSS:3.0/AV:A/AC:L/PR:L/UI:N/S:U/C:H/I:H/A:H/CR:M/IR:M/AR:M/MAV:A/MAC:H/MUI:R/MS:U/MC:L/MI:L/MA:L', 8.0, 8.0, 4.3],
            ['CVSS:3.0/AV:A/AC:L/PR:L/UI:N/S:U/C:H/I:H/A:H/CR:M/IR:M/AR:M/MAV:A/MAC:H/MUI:R/MS:U/MC:L/MI:L/MA:L', 8.0, 8.0, 4.3],
            ['CVSS:3.0/AV:N/AC:L/PR:N/UI:N/S:C/C:H/I:H/A:H/E:U/RL:T/RC:U/CR:L/IR:L/AR:H/MAV:P/MAC:H/MPR:H/MUI:R/MS:C/MC:L/MI:H/MA:H', 10.0, 8.1, 5.6],
            ['CVSS:3.0/AV:N/AC:L/PR:N/UI:N/S:C/C:H/I:H/A:H/E:U/RL:T/RC:U/CR:L/IR:L/AR:H/MAV:P/MAC:H/MPR:H/MUI:R/MS:C/MC:H/MI:H/MA:H', 10.0, 8.1, 5.5],

            ['CVSS:2/AV:N/AC:L/Au:N/C:C/I:C/A:C', 10.0, 10.0, 10.0],
            ['CVSS:2/AV:N/AC:L/Au:N/C:C/I:C/A:C/E:U/RL:OF/RC:UC', 10.0, 6.7, 6.7],
            ['CVSS:2/AV:N/AC:L/Au:N/C:C/I:C/A:C/E:U/RL:OF/RC:UC/CDP:L/TD:L/CR:M/IR:M/AR:M', 10.0, 6.7, 1.7],
            ['CVSS:2/AV:N/AC:L/Au:N/C:N/I:N/A:C/E:F/RL:OF/RC:C/CDP:H/TD:H/CR:M/IR:M/AR:H', 7.8, 6.4, 9.1],
            ['CVSS:2/AV:N/AC:L/Au:N/C:C/I:C/A:C/E:F/RL:OF/RC:C/CDP:H/TD:H/CR:M/IR:M/AR:L', 10.0, 8.3, 9.0],
            ['CVSS:2/AV:L/AC:H/Au:N/C:C/I:C/A:C/E:POC/RL:OF/RC:C/CDP:H/TD:H/CR:M/IR:M/AR:M', 6.2, 4.9, 7.4],
            ['AV:N/AC:L/Au:N/C:C/I:C/A:C', 10.0, 10.0, 10.0],
            ['AV:N/AC:L/Au:N/C:C/I:C/A:C/E:U/RL:OF/RC:UC', 10.0, 6.7, 6.7],
            ['AV:L/AC:H/Au:N/C:C/I:C/A:C/E:POC/RL:OF/RC:C/CDP:H/TD:H/CR:M/IR:M/AR:M', 6.2, 4.9, 7.4],
        ];
    }

    /**
     * @dataProvider invalidVectorProvider
     */
    public function testInvalidVectors(string $vector): void
    {
        $this->expectException(CvssException::class);

        Cvss::generateScores($vector);
    }

    public static function invalidVectorProvider(): array
    {
        return [
            ['CVSS:3.1/AV:A/AC:L/PR:L/UI:N/S:U/'],
            ['CVSS:3/AV:P/AC:H/PR:H/UI:R/S:U/C:N/I:N/A:N'],
            ['CVSS:2/AV:P/AC:H/PR:H/UI:R/S:U/C:N/I:N/A:N'],
            ['CVSS:1/AV:P/AC:H/PR:N/UI:R/S:C/C:L/I:H/A:N'],
        ];
    }

    /**
     * @dataProvider invalidVersionProvider
     */
    public function testInvalidCalculator(int|float $version): void
    {
        $this->expectExceptionCode(CvssException::class);
        $this->expectExceptionMessage('The vector you have provided is invalid');
        $this->expectExceptionCode(403);
        
        $reflectCvss = new \ReflectionClass(Cvss::class);
        $method = $reflectCvss->getMethod('buildCalculator');
        $method->setAccessible(true);
        
        $cvs = new Cvss();
        $method->invokeArgs($cvs, ['version' => $version]);
    }
    
    public static function invalidVersionProvider(): array
    {
        return [
            [1],
            [3.2],
            [4],
            [5],
        ];
    }
}
