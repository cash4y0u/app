@component('mail::message')
# Olá Tiago

Um novo empréstimo de **{{$contract->formattedAmount}}** foi feito para o **{{$contract->customer->name}}** em **{{$contract->split}} X** com a taxa de **{{$contract->rate}}**.

# Parcelas do empréstimo

@component('mail::table')
| Número    | Valor   | Vencimento  |
|:------:   |:-----------   |:--------: |
@foreach($contract->provisions as $provision)
| {{$provision->number}}     | {{$provision->formattedAmount}} |        {{$provision->formattedMaturity}} |
@endforeach
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
