<?php

final class SmokeTestResult
{
    private int $passed = 0;
    private int $failed = 0;
    private array $failures = [];

    public function pass(string $name, string $detail = ''): void
    {
        $this->passed++;
        $this->printLine('PASS', $name, $detail);
    }

    public function fail(string $name, string $detail = ''): void
    {
        $this->failed++;
        $this->failures[] = [$name, $detail];
        $this->printLine('FAIL', $name, $detail);
    }

    public function check(bool $condition, string $name, string $passDetail = '', string $failDetail = ''): void
    {
        if ($condition) {
            $this->pass($name, $passDetail);
            return;
        }

        $this->fail($name, $failDetail);
    }

    public function summary(): int
    {
        echo PHP_EOL;
        echo 'Tổng kết smoke test' . PHP_EOL;
        echo 'Đạt: ' . $this->passed . PHP_EOL;
        echo 'Lỗi: ' . $this->failed . PHP_EOL;

        if ($this->failed > 0) {
            echo PHP_EOL . 'Danh sách lỗi:' . PHP_EOL;
            foreach ($this->failures as [$name, $detail]) {
                echo '- ' . $name;
                if ($detail !== '') {
                    echo ': ' . $detail;
                }
                echo PHP_EOL;
            }
        }

        return $this->failed === 0 ? 0 : 1;
    }

    private function printLine(string $status, string $name, string $detail): void
    {
        $statusLabel = $status === 'PASS' ? 'ĐẠT' : 'LỖI';
        $line = '[' . $statusLabel . '] ' . $name;
        if ($detail !== '') {
            $line .= ' - ' . $detail;
        }

        echo $line . PHP_EOL;
    }
}
