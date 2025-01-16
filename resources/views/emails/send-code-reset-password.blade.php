@component('mail::message')
<h1>لقد تلقينا طلبك لإعادة تعيين كلمة مرور حسابك</h1>
<p>يمكنك استخدام الكود التالي لاستعادة حسابك:</p>

@component('mail::panel')
{{ $code }}
@endcomponent

<p>المدة المسموح بها للرمز هي ساعة واحدة من وقت إرسال الرسالة</p>
@endcomponent