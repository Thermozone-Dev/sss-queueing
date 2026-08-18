<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <title>SSS Appointment Confirmation</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: Arial, sans-serif;">

    <div style="max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden;">

        {{-- Header --}}
        <div style="background-color: #0054a6; padding: 30px; text-align: center;">

            <h1 style="margin: 0; color: #ffffff; font-size: 24px;">
                SSS Appointment
            </h1>

            <p style="margin: 8px 0 0; color: #dbeafe;">
                Appointment Confirmation
            </p>

        </div>


        {{-- Content --}}
        <div style="padding: 30px;">

            <h2 style="margin-top: 0; color: #111827;">
                Appointment Confirmed
            </h2>

            <p style="color: #4b5563; line-height: 1.6;">
                Hello {{ $appointment['member_name'] ?? 'Member' }},
            </p>

            <p style="color: #4b5563; line-height: 1.6;">
                Your SSS appointment has been successfully scheduled.
                Please see your appointment details below.
            </p>


            {{-- Appointment Details --}}
            <div style="margin-top: 25px; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden;">

                <div style="padding: 15px; background-color: #f9fafb;">
                    <strong style="color: #111827;">
                        Appointment Details
                    </strong>
                </div>

                <div style="padding: 20px;">

                    <p style="margin: 0 0 12px; color: #4b5563;">
                        <strong>Reference:</strong>
                        {{ $appointment['reference'] }}
                    </p>

                    <p style="margin: 0 0 12px; color: #4b5563;">
                        <strong>Branch:</strong>
                        {{ $appointment['branch'] }}
                    </p>

                    <p style="margin: 0 0 12px; color: #4b5563;">
                        <strong>Transaction:</strong>
                        {{ $appointment['transaction'] }}
                    </p>

                    <p style="margin: 0 0 12px; color: #4b5563;">
                        <strong>Date:</strong>
                        {{ $appointment['date'] }}
                    </p>

                    <p style="margin: 0; color: #4b5563;">
                        <strong>Time:</strong>
                        {{ $appointment['time'] }}
                    </p>

                </div>

            </div>


            {{-- Reminder --}}
            <div style="margin-top: 25px; padding: 15px; background-color: #eff6ff; border-radius: 8px;">

                <p style="margin: 0; color: #1e40af; font-size: 14px; line-height: 1.5;">
                    Please arrive at the selected SSS branch before your appointment time.
                </p>

            </div>


            <p style="margin-top: 30px; color: #4b5563; line-height: 1.6;">
                Thank you for using SSS Online Appointment Services.
            </p>

        </div>


        {{-- Footer --}}
        <div style="padding: 20px; background-color: #f9fafb; text-align: center;">

            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                This is an automated email. Please do not reply.
            </p>

        </div>

    </div>

</body>

</html>
