(see this milestone: https://github.com/LaSalleSoftware/ls-librarybackend-pkg/milestone/39)

Today is July 2025.

After much time and grief, the bottom line is that I cannot get Nova 5.4.3 to work. 

I was using Nova 5.0.3 (give or take) in production for the longest time, because newer Nova versions were causing 404 errors. 

I learned that, at least partially, the real error was something else. However, it is moot. 

I am 99% certain that the problem is that Laravel's Fortify package does not play nicely with my custom authentication. Nova version 5 uses Laravel's Fortify package. Fortify is Laravel's AUTH without the front-end. 

Well, Nova is not just the CRUD screens and stuff. It wants to be the full app, replete with all the fun AUTH stuff. Because I am the only LaSalle Software user + I have just one single production LaSalle Software back-end + there is just one single solitary login user of this app, I can use Nova/Fortify as the AUTH. 

For this librarybackend package, I am deleting all the AUTH, and whatever else I find that needs pruning. From here on in, using the Laravel AUTH, not my custom AUTH. I will tag this as VERSION 4. 

