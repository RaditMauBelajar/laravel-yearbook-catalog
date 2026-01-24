<h2>Inquiry Baru</h2>
<p><b>Nama:</b> {{ $inquiry->name }}</p>
<p><b>Email:</b> {{ $inquiry->email }}</p>
<p><b>No HP:</b> {{ $inquiry->phone }}</p>
<p><b>Judul:</b> {{ $inquiry->subject }}</p>

<hr>

<p><b>Pesan:</b></p>
<p>{{ $messageModel->message }}</p>

<p>
  Buka di dashboard:
  <a href="{{ route('admin.inquiries.show', $inquiry->id) }}">Lihat Detail</a>
</p>
