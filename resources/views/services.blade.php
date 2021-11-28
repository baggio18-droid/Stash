@extends('layouts.welcome')

@section('content')
<div class="container-fluid">
<div class="headerParagraph" >
<h1 style="text-align:center">Our Services</h1>

</div>

<style>
   body
   * {
      box-sizing: border-box;
   }
   .card {
      color: white;
      float: left;
      width: calc(50% - 40px);
      padding: 10px;
      border-radius: 10px;
      margin: 10px;
      height: 250px;
   }


   .card p {
      font-size: 18px;
   }
   .cardContainer:after {
      content: "";
      display: table;
      clear: both;
   }
   @media screen and (max-width: 600px) {
      .card {
         width: 100%;
      }
   }

   img:hover {
  /* Start the shake animation and make the animation last for 0.5 seconds */
  animation: shake 0.5s;

  /* When the animation is finished, start again */
  animation-iteration-count: infinite;
}

@keyframes shake {
  0% { transform: translate(1px, 1px) rotate(0deg); }
  10% { transform: translate(-1px, -2px) rotate(-1deg); }
  20% { transform: translate(-3px, 0px) rotate(1deg); }
  30% { transform: translate(3px, 2px) rotate(0deg); }
  40% { transform: translate(1px, -1px) rotate(1deg); }
  50% { transform: translate(-1px, 2px) rotate(-1deg); }
  60% { transform: translate(-3px, 1px) rotate(0deg); }
  70% { transform: translate(3px, 1px) rotate(-1deg); }
  80% { transform: translate(-1px, -1px) rotate(1deg); }
  90% { transform: translate(1px, 2px) rotate(0deg); }
  100% { transform: translate(1px, -2px) rotate(-1deg); }
}

.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 70%;
}

</style>

<div class="services">
<div class="headerParagraph">
<h2>Locker - Small Size</h2>
<div class="cardContainer" >
<div class="card" >
<img src="/img/locker.png" width="100" height="200" class="center">
</div>

<div class="card" width="400" >
    <p style="color:black">
    When organized appropriately, a 3x3 storage locker can fit all kinds of things.
    The list of items you can store in your climate-controlled storage locker include:
    Clothes, shoes, small musical instruments (flute, clarinet, etc.),
    delivered mail and packages, highly valuable possessions (such as rare comic books, stamp collections, coins, etc.),
    books, DVDS, and video games,
    Electronics (TVs, Blu-Ray players, DVD players, video game consoles, etc.),
    pillows and sheets.
    The list goes on and on!
    </p>
</div>

</div>

</div><br>
<div class="headerParagraph">
<h2>Garage - Medium Size</h2>
<div class="cardContainer" >
<div class="card" style="color:black">
<img src="/img/garage.png" width="100" height="200" class="center">
</div>

<div class="card">
<p style="color:black">
The 6 x 6 storage unit that we offer is ideal for all those items that are too large to keep housed at your home whether it’s a small boat,
a bakkie, car storage or other items that you actually don’t need on hand all the time like industrial equipment, 
steel drums, and other items that are just too cumbersome. 
When it comes to the protection of your goods  which means that the unit will house large and expensive items, our team  will ensure that they are safe at all times.
    </p>
</div>
</div>
</div><br>

<div class="headerParagraph">
<h2>Warehouse - Large Size</h2>
<div class="cardContainer" >
<div class="card" style="color:black">
<img src="/img/warehouse.png" width="100" height="170" class="center">
</div>

<div class="card">
<p style="color:black">
The functions of warehousing include stocking, maintaining, and controlling your work in 
process inventory. With 9 x 9 space, it is Developing a dependable warehouse process for your products is 
crucial for business growth. 
Warehousing actions include: Setting your warehouse up properly and with relevant equipment. 
Stored goods can include any raw materials, packing materials, components, or finished goods associated 
with agriculture, manufacturing, and production.
    </p>
</div>
</div>
</div>
</div>
@endsection