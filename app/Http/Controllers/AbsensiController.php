<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function absenMasuk()
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return response()->json(['message' => 'User belum memiliki relasi employee.'], 422);
        }

        $today = Carbon::now()->toDateString();

        $existing = Absensi::where('employee_id', $employee->id)->where('tanggal', $today)->first();
        if ($existing && $existing->jam_masuk) {
            return response()->json(['message' => 'Sudah absen masuk hari ini.'], 422);
        }

        $now = Carbon::now();
        $isLate = $now->greaterThan(Carbon::createFromTime(8, 0, 0)); // lewat jam 8
        $keterangan = $isLate ? 'Telat' : 'Hadir';

        $absensi = Absensi::updateOrCreate(
            ['employee_id' => $employee->id, 'tanggal' => $today],
            ['jam_masuk' => $now->toTimeString(), 'keterangan' => $keterangan]
        );

        return response()->json($absensi);
    }

    public function absenPulang()
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return response()->json(['message' => 'User belum memiliki relasi employee.'], 422);
        }

        $today = Carbon::now()->toDateString();

        $absensi = Absensi::where('employee_id', $employee->id)->where('tanggal', $today)->first();

        if (!$absensi || !$absensi->jam_masuk) {
            return response()->json(['message' => 'Belum absen masuk.'], 422);
        }

        if ($absensi->jam_pulang) {
            return response()->json(['message' => 'Sudah absen pulang.'], 422);
        }

        if (Carbon::now()->lessThan(Carbon::createFromTime(16, 0, 0))) {
            return response()->json(['message' => 'Belum waktunya pulang. Absen pulang hanya bisa dilakukan setelah jam 16:00.'], 422);
        }        

        $absensi->update([
            'jam_pulang' => Carbon::now()->toTimeString()
        ]);

        return response()->json($absensi);
    }

        public function histori()
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return response()->json(['message' => 'User belum memiliki relasi employee.'], 422);
        }

        $absensis = Absensi::where('employee_id', $employee->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json($absensis);
    }

}
