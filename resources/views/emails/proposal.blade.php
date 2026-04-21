<div style="font-family: 'Segoe UI', sans-serif; background: #f4f6fa; padding: 32px;">
    <div style="max-width: 600px; margin: auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(30,115,190,0.08); padding: 32px;">
        <h2 style="color: #1e73be;">Dear {{ $proposal->lead->name }},</h2>
        <p>Thank you for considering Calcutta Corporate. Please find your proposal attached as a PDF.</p>
        <p><strong>Proposal:</strong> {{ $proposal->title }}</p>
        <p><strong>Total Amount:</strong> ₹{{ number_format($proposal->total_amount, 2) }}</p>
        <hr>
        <p>If you have any questions or would like to discuss further, feel free to reply to this email.</p>
        <p>Best regards,<br>Calcutta Corporate Team</p>
    </div>
</div>
