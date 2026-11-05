<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
  .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #1F0055, #38009A); padding: 32px 36px; }
  .header h1 { color: #fff; margin: 0; font-size: 20px; font-weight: 700; }
  .header p { color: rgba(255,255,255,0.7); margin: 6px 0 0; font-size: 14px; }
  .body { padding: 32px 36px; }
  .stage-badge { display: inline-block; background: #EBE6F5; color: #38009A; padding: 4px 14px; border-radius: 999px; font-size: 13px; font-weight: 600; margin: 0 0 20px; }
  .message { color: #374151; font-size: 15px; line-height: 1.6; }
  .info-row { display: flex; gap: 8px; margin: 6px 0; font-size: 14px; color: #374151; }
  .info-label { color: #9CA3AF; width: 100px; flex-shrink: 0; }
  .note-box { background: #FFFBEB; border-left: 3px solid #F59E0B; padding: 14px 18px; border-radius: 0 8px 8px 0; color: #374151; font-size: 14px; line-height: 1.6; margin: 20px 0; }
  .footer { padding: 20px 36px; border-top: 1px solid #E5E7EB; color: #9CA3AF; font-size: 12px; }
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1>Pipeline Update</h1>
    <p>{{ $application->job?->title }}</p>
  </div>
  <div class="body">
    <div class="stage-badge">→ {{ $stageName }}</div>
    <div class="message">
      <p>A candidate has been moved to the <strong>{{ $stageName }}</strong> stage.</p>
      <div class="info-row"><span class="info-label">Candidate:</span> <strong>{{ $application->candidate?->user?->first_name }} {{ $application->candidate?->user?->last_name }}</strong></div>
      <div class="info-row"><span class="info-label">Job:</span> {{ $application->job?->title }}</div>
      <div class="info-row"><span class="info-label">Stage:</span> {{ $stageName }}</div>
      @if(!empty($automation->action_config['notes']))
      <div class="note-box"><strong>Note:</strong> {{ $automation->action_config['notes'] }}</div>
      @endif
    </div>
  </div>
  <div class="footer">This is an internal notification from the Jobify platform.</div>
</div>
</body>
</html>
