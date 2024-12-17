@extends('layouts.adminLayout')

@section('content')
<div class="main-content">
    <div class="main-inner">
        <div class="main-heading">
            <h2>Welcome, {{$userName}}</h2>
        </div>

        <div class="cardBox">
            <div class="card">
                <div>
                    <div class="numbers">504</div>
                    <div class="cardName">Total Appointments</div>
                </div>

                <div class="iconBx">
                    <ion-icon name="eye-outline"></ion-icon>
                </div>
            </div>

            <div class="card">
                <div>
                    <div class="numbers">12</div>
                    <div class="cardName">Number Of Doctors</div>
                </div>

                <div class="iconBx">
                    <ion-icon name="cart-outline"></ion-icon>
                </div>
            </div>

            <div class="card">
                <div>
                    <div class="numbers">3</div>
                    <div class="cardName">Number Of Clinics</div>
                </div>

                <div class="iconBx">
                    <ion-icon name="chatbubbles-outline"></ion-icon>
                </div>
            </div>

            <div class="card">
                <div>
                    <div class="numbers">1</div>
                    <div class="cardName">Cancled Appointments</div>
                </div>

                <div class="iconBx">
                    <ion-icon name="cash-outline"></ion-icon>
                </div>
            </div>
        </div>

        </div>
        
    </div>
</div>
@endsection
