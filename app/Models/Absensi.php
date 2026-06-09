<?php

namespace App\Models;

use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'pengguna_id',
        'jenis',
        'waktu',
        'tanggal',
        'latitude',
        'longitude',
        'alamat',
        'foto',
        'catatan',
        'status',
        'menit_terlambat',
    ];

    protected $casts = [
        'tanggal'           => 'date',
        'waktu'             => 'string',
        'latitude'          => 'decimal:6',
        'longitude'         => 'decimal:6',
        'menit_terlambat'   => 'integer', 
    ];

    // Relasi
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    // Scope Filter Tanggal
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', today());
    }

    public function scopeTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }

    // Filter absensi dalam rentang tanggal
    public function scopeRentang($query, $dari, $sampai)
    {
        return $query->whereDate('tanggal', '>=', $dari)->whereDate('tanggal', '<=', $sampai);
    }

    // Filter absensi bulan dan tahun tertentu
    public function scopeBulan($query, int $bulan, int $tahun)
    {
        return $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
    }

    // Scope Filter Jenis
    public function scopeHadir($query)
    {
        return $query->where('jenis', 'Hadir');
    }

    public function scopePulang($query)
    {
        return $query->where('jenis', 'Pulang');
    }

    public function scopeIzin($query)
    {
        return $query->where('jenis', 'Izin');
    }

    public function scopeSakit($query)
    {
        return $query->where('jenis', 'Sakit');
    }

    // Scope Filter Status
    public function scopeTerlambat($query)
    {
        return $query->where('status', 'Terlambat');
    }

    public function scopeTepat($query)
    {
        return $query->where('status', 'Tepat Waktu');
    }

    // Scope Filter pengguna dan role
    public function scopeFilterPengguna($query, int $penggunaId)
    {
        return $query->where('pengguna_id', $penggunaId);
    }

    // Filter role pengguna
    public function scopeRole($query, string $role)
    {
        return $query->whereHas('pengguna', fn($q) => $q->where('role', $role));
    }

    // Accessor - Format Tampilan
    // Format waktu
    public function getWaktuFormatAttribute(): string
    {
        return Carbon::parse($this->waktu)->format('H:i');
    }

    // Format Tanggal
    public function getTanggalFormatAttribute(): string
    {
        return Carbon::parse($this->tanggal)->translatedFormat('l, d F Y');
    }

    // Format Keterlambatan
    public function getTelatFormatAttribute(): string
    {
        if ($this->menit_terlambat <= 0) return '-';

        $jam    = intdiv($this->menit_terlambat, 60);
        $menit  = $this->menit_terlambat % 60;

        $result = '';
        if ($jam > 0) $result .= $jam . ' jam ';
        if ($menit > 0) $result .= $menit . ' menit';

        return trim($result);
    }

    // URL foto absensi
    public function getFotoUrlAttribute(): string
    {
        return asset('storage/' . $this->foto);
    }

    // badge HTML status (tepat / terlambat)
    public function getStatusBadgeAttribute(): string
    {
        return $this->isTerlambat()
            ? '<span class="badge bg-danger">⚠️ Terlambat</span>'
            : '<span class="badge bg-success">✅ Tepat Waktu</span>';
    }

    // badge html jenis absensi
    public function getJenisBadgeAttribute(): string
    {
        $map = [
            'Hadir'     => 'success',
            'Pulang'    => 'secondary',
            'Izin'      => 'warning',
            'Sakit'     => 'danger',
        ];

        $color = $map[$this->jenis] ?? 'primary';

        return "<span class=\"badge bg-{$color}\">{$this->jenis}</span>";
    }

    // Pengecekan boolean
    public function isTerlambat(): bool
    {
        return $this->status === 'Terlambat';
    }

    public function isTepat(): bool
    {
        return $this->status === 'Tepat Waktu';
    }

    public function isHadir(): bool
    {
        return $this->jenis === 'Hadir';
    }

    public function isPulang(): bool
    {
        return $this->jenis === 'Pulang';
    }

    public function isIzin(): bool
    {
        return $this->jenis === 'Izin';
    }

    public function isSakit(): bool
    {
        return $this->jenis === 'Sakit';
    }
}
