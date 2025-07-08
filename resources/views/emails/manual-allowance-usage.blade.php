<p>Hello {{ $record->customer->name }},</p>

<p>An amount of <strong>{{ number_format($record->delta) }}</strong> has been manually added to your allowance.</p>

<p><strong>Description:</strong> {{ $record->description }}</p>
<p><strong>New Balance:</strong> {{ number_format($record->balance) }}</p>

<p>Thank you,<br>HR Department</p>
