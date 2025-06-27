(see this milestone: https://github.com/LaSalleSoftware/ls-librarybackend-pkg/milestone/39)

Today is June 2025.

I have come upon the reason why my site works, but my Nova fails, when I update Nova to a later version of 5.x.

Nova 4 used Laravel UI.

Laravel UI is a bridge, allowing Laravel apps to use the old way of doing AUTH. Because those controllers and stuff were extracted out of Illuminate. Laravel UI was a tricky way of preserving those controllers and stuff with the old namespaces. I use Laravel UI.

Well, the Fortify package is the new bare-bones AUTH with which to build your own custom routes and UI. Whatever! 

The key thing is that Nova 5 does not use Laravel UI. It uses the Fortify package. 

And, in doing so, is causing me tons of grief. For zero -- ZERO -- feature benefits. 

To my shock, and it was an adventure finding out, that my custom LaSalleGuard does not work with Fortify, because, Bless Their Heart!, the Fortify controllers implement the "Stateful Guard" interface. My LaSalleGuard does not so implement the StatefulGuard interface. 

Thankfully, it looks like it does not matter if I just so happen to, after, what, half a decade? More? Just casually change my custom LaSalleGuard to implement StatefulGuard. 

Because, you see, Nova is really designed to be an all-in-one app. It is an app, disguised as a package. And, for this reason, in hindsight, I should never have used it. Well, it has been one PITA after another with Nova. That it works so well with Policies is a plus...

All I wanted was nice CRUD screens. 

I always thought that "resources" should have been an FOSS concept. Free as in not having to buy something that does it, and having an entire community rally around it. 

There is a database/Schema, Eloquent/Models, Policies. What was always missing was missing, and probably not by accident, was The Form. Since the "type" was derived from the Schema, you could do a basic form based on that schema. Yes, there are a million details, and relationships sure are fun aren't they. I went to Nova for this missing "resource" thing, but I stayed for the bullshit I have to endure to using it. Very much feels like a "sunk cost". 

For all the grief Nova has caused me over the years, and for the absolutely basic way I use Nova, it is not over-the-top to look back and wonder if I would have been better off doing my own thing. Filament just keeps looking more attractive with each passing week.

Anyhoo... 

So far, it looks like one single line of code, that took forever to discover, is needed. But, I think, the adventure will continue beyond that.