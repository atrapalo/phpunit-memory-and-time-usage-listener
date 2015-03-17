# PHPUnit Memory & Time usage Listener

This PHPUnit listener provides information about each test time, memory and memory peak usage, with two versions configured by parameters:

 - One in which at the end of the test suits shows the information for all the executed test.
 - One in which you indicates the edge for all the measurements and it only shows the results if one ot them exceed it's edge.

If no parameter is defined on the phpunit.xml the default behaviour is to show all the results.


The arguments you can define on the phpunit.xml file are:

 - showOnlyIfEdgeIsExceeded (boolean): The default value is false, if you set it to true is recommended to set the other three optional arguments.
 - executionTimeEdge (int)(milliseconds): Only needed if `showOnlyIfEdgeIsExceeded` is set to true. The default value is 100 and indicates the limit of usage time for every test
 - memoryUsageEdge (int)(bytes): Only needed if `showOnlyIfEdgeIsExceeded` is set to true. The default value is 1024 and indicates indicate the limit of memory usage for every usage
 - memoryPeakDifferenceEdge (int)(bytes): Only needed if `showOnlyIfEdgeIsExceeded` is set to true. The default value is 1024 and indicates the maximum difference between memory peak.

## Installation:

The installation for use this listener it's as easy as require it with composer as any other. I recommend to indicate the requirement only for dev.

```json

require-dev{
    "atrapalo/phpunit-memory-and-time-usage-listener": "dev-master"
}

```

## Usage examples:

XML example for listener usage without edges:

```xml
    <listener class="\PhpunitMemoryAndTimeUsageListener\Listener\Measurement\TimeAndMemoryTestListener"/>
```

XML example for listener usage with edges:

```xml
     <listeners>
         <listener class="\PhpunitMemoryAndTimeUsageListener\Listener\Measurement\TimeAndMemoryTestListener">
             <arguments>
                 <array>
                     <element key="showOnlyIfEdgeIsExceeded">
                         <boolean>true</boolean>
                     </element>
                     <element key="executionTimeEdge">
                         <integer>100</integer>
                     </element>
                     <element key="memoryUsageEdge">
                         <integer>1024</integer>
                     </element>
                     <element key="memoryPeakDifferenceEdge">
                         <integer>10240</integer>
                     </element>
                 </array>
             </arguments>
         </listener>
     </listeners>
```
