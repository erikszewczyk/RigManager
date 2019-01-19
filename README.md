# Introduction 
<h2>What is RigManager?</h2>
<p>Rig Manager is a service for monitoring your cryptocurrency mining rigs that is designed to be:</p>
<ul align='left'>
    <li>Fast and effective at answering the key question – “Are my miners running and how are they doing?”</li>
    <li>Able to be integrated with any miner hardware (ASIC, FPGA, GPU, and others) and any mining software (MPM, CCMiner, Bminer, etc.) based on an API that’s simple to interface with</li>
    <li>Store/display history/mining statistics</li>
    <li>Have as little personal information as possible about you or your mining operation</li>
    <li>Have a free to use option (as in “Free Beer”) – core functionality without any dev or subscription fees</li>
</ul>

You can use our site for free at: https://www.rigmanager.xyz/

Or use this code to selfhost (or if you're curious what our site does)

NOTE that our code and hosting utilizes Microsoft Azure's native authentication integration capabilities with Google - if you do decide to self host you'll need to do something on your own to create authentication.

Have fun!

# Getting Started
If you're serious about self hosting rather than using ours you'll need to:
1.	Create your back-end DB using the provided sql scripts
2. Modify mysqlcon.php with your DB connectivity string
3.	Create an authentication regeme and modify the php code as required
4.	Upload to your site

# Build and Test
We have our own build and test process as part of our CI/CD pipeline for our hosted site - if you self host given the changes you'll need to make we suggest you do the same.  If you know how to self-host we assume you also understand how to run a build and test process!

# Contribute
<p>Share your feedback on Reddit: https://www.reddit.com/r/RigManager</p>
<p>Any bitcoin donations are greatly appreciated: 3DHjBqPWFZzg6jo6f2DVcZc4t5bZJy38ZH</p>
