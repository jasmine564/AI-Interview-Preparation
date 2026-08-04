<?php
// backend/problems_data.php

$problems = [
    // --- ARRAYS ---
    [
        'title' => 'Two Sum',
        'slug' => 'two-sum',
        'difficulty' => 'Easy',
        'topic' => 'Array',
        'description' => 'Given an array of integers `nums` and an integer `target`, return indices of the two numbers such that they add up to `target`.',
        'examples' => json_encode([
            ["input" => "nums = [2,7,11,15], target = 9", "output" => "[0,1]"],
            ["input" => "nums = [3,2,4], target = 6", "output" => "[1,2]"]
        ]),
        'test_cases' => json_encode([
            ["input" => "2 7 11 15\n9", "output" => "0 1"],
            ["input" => "3 2 4\n6", "output" => "1 2"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def twoSum(self, nums: List[int], target: int) -> List[int]:\n        ",
            'javascript' => "var twoSum = function(nums, target) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    lines = sys.stdin.read().strip().splitlines()\n    nums = list(map(int, lines[0].split()))\n    target = int(lines[1])\n    if 'Solution' in globals(): print(f\"{Solution().twoSum(nums, target)[0]} {Solution().twoSum(nums, target)[1]}\")\n    elif 'twoSum' in globals(): print(f\"{twoSum(nums, target)[0]} {twoSum(nums, target)[1]}\")",
            'javascript' => "const fs = require('fs');\nconst lines = fs.readFileSync(0, 'utf-8').trim().split('\\n');\nconst nums = lines[0].trim().split(' ').map(Number);\nconst target = Number(lines[1]);\nlet res = (typeof Solution === 'function') ? new Solution().twoSum(nums, target) : twoSum(nums, target);\nconsole.log(res.join(' '));"
        ])
    ],
    [
        'title' => 'Best Time to Buy and Sell Stock',
        'slug' => 'best-time-to-buy-and-sell-stock',
        'difficulty' => 'Easy',
        'topic' => 'Array',
        'description' => 'You are given an array `prices` where `prices[i]` is the price of a given stock on the `i`th day. You want to maximize your profit by choosing a single day to buy one stock and choosing a different day in the future to sell that stock. Return the maximum profit you can achieve from this transaction. If you cannot achieve any profit, return 0.',
        'examples' => json_encode([
            ["input" => "prices = [7,1,5,3,6,4]", "output" => "5", "explanation" => "Buy on day 2 (price = 1) and sell on day 5 (price = 6), profit = 6-1 = 5."],
            ["input" => "prices = [7,6,4,3,1]", "output" => "0", "explanation" => "In this case, no transactions are done and the max profit = 0."]
        ]),
        'test_cases' => json_encode([
            ["input" => "7 1 5 3 6 4", "output" => "5"],
            ["input" => "7 6 4 3 1", "output" => "0"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def maxProfit(self, prices: List[int]) -> int:\n        ",
            'javascript' => "/**\n * @param {number[]} prices\n * @return {number}\n */\nvar maxProfit = function(prices) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    prices = list(map(int, sys.stdin.read().strip().split()))\n    if 'Solution' in globals(): print(Solution().maxProfit(prices))\n    elif 'maxProfit' in globals(): print(maxProfit(prices))",
            'javascript' => "const fs = require('fs');\nconst prices = fs.readFileSync(0, 'utf-8').trim().split(' ').map(Number);\nif (typeof Solution === 'function') console.log(new Solution().maxProfit(prices));\nelse console.log(maxProfit(prices));"
        ])
    ],
    [
        'title' => 'Product of Array Except Self',
        'slug' => 'product-of-array-except-self',
        'difficulty' => 'Medium',
        'topic' => 'Array',
        'description' => 'Given an integer array `nums`, return an array `answer` such that `answer[i]` is equal to the product of all the elements of `nums` except `nums[i]`. The product of any prefix or suffix of `nums` is guaranteed to fit in a 32-bit integer. You must write an algorithm that runs in O(n) time and without using the division operation.',
        'examples' => json_encode([
            ["input" => "nums = [1,2,3,4]", "output" => "[24,12,8,6]"],
            ["input" => "nums = [-1,1,0,-3,3]", "output" => "[0,0,9,0,0]"]
        ]),
        'test_cases' => json_encode([
            ["input" => "1 2 3 4", "output" => "24 12 8 6"],
            ["input" => "-1 1 0 -3 3", "output" => "0 0 9 0 0"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def productExceptSelf(self, nums: List[int]) -> List[int]:\n        ",
            'javascript' => "/**\n * @param {number[]} nums\n * @return {number[]}\n */\nvar productExceptSelf = function(nums) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    nums = list(map(int, sys.stdin.read().strip().split()))\n    res = []\n    if 'Solution' in globals(): res = Solution().productExceptSelf(nums)\n    elif 'productExceptSelf' in globals(): res = productExceptSelf(nums)\n    print(' '.join(map(str, res)))",
            'javascript' => "const fs = require('fs');\nconst nums = fs.readFileSync(0, 'utf-8').trim().split(' ').map(Number);\nlet res = (typeof Solution === 'function') ? new Solution().productExceptSelf(nums) : productExceptSelf(nums);\nconsole.log(res.join(' '));"
        ])
    ],
    [
        'title' => 'Maximum Subarray',
        'slug' => 'maximum-subarray',
        'difficulty' => 'Medium',
        'topic' => 'Array',
        'description' => 'Given an integer array `nums`, find the subarray with the largest sum, and return its sum.',
        'examples' => json_encode([
            ["input" => "nums = [-2,1,-3,4,-1,2,1,-5,4]", "output" => "6", "explanation" => "The subarray [4,-1,2,1] has the largest sum 6."],
            ["input" => "nums = [1]", "output" => "1"],
            ["input" => "nums = [5,4,-1,7,8]", "output" => "23"]
        ]),
        'test_cases' => json_encode([
            ["input" => "-2 1 -3 4 -1 2 1 -5 4", "output" => "6"],
            ["input" => "1", "output" => "1"],
            ["input" => "5 4 -1 7 8", "output" => "23"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def maxSubArray(self, nums: List[int]) -> int:\n        ",
            'javascript' => "/**\n * @param {number[]} nums\n * @return {number}\n */\nvar maxSubArray = function(nums) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    nums = list(map(int, sys.stdin.read().strip().split()))\n    if 'Solution' in globals(): print(Solution().maxSubArray(nums))\n    elif 'maxSubArray' in globals(): print(maxSubArray(nums))",
            'javascript' => "const fs = require('fs');\nconst nums = fs.readFileSync(0, 'utf-8').trim().split(' ').map(Number);\nif (typeof Solution === 'function') console.log(new Solution().maxSubArray(nums));\nelse console.log(maxSubArray(nums));"
        ])
    ],

    // --- STRINGS ---
    [
        'title' => 'Longest Common Prefix',
        'slug' => 'longest-common-prefix',
        'difficulty' => 'Easy',
        'topic' => 'String',
        'description' => 'Write a function to find the longest common prefix string amongst an array of strings. If there is no common prefix, return an empty string "".',
        'examples' => json_encode([
            ["input" => "strs = [\"flower\",\"flow\",\"flight\"]", "output" => "\"fl\""],
            ["input" => "strs = [\"dog\",\"racecar\",\"car\"]", "output" => "\"\""]
        ]),
        'test_cases' => json_encode([
            ["input" => "flower flow flight", "output" => "fl"],
            ["input" => "dog racecar car", "output" => ""]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def longestCommonPrefix(self, strs: List[str]) -> str:\n        ",
            'javascript' => "/**\n * @param {string[]} strs\n * @return {string}\n */\nvar longestCommonPrefix = function(strs) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    strs = sys.stdin.read().strip().split()\n    if 'Solution' in globals(): print(Solution().longestCommonPrefix(strs))\n    elif 'longestCommonPrefix' in globals(): print(longestCommonPrefix(strs))",
            'javascript' => "const fs = require('fs');\nconst strs = fs.readFileSync(0, 'utf-8').trim().split(' ');\nif (typeof Solution === 'function') console.log(new Solution().longestCommonPrefix(strs));\nelse console.log(longestCommonPrefix(strs));"
        ])
    ],
    [
        'title' => 'Valid Anagram',
        'slug' => 'valid-anagram',
        'difficulty' => 'Easy',
        'topic' => 'String',
        'description' => 'Given two strings s and t, return true if t is an anagram of s, and false otherwise.',
        'examples' => json_encode([
            ["input" => "s = \"anagram\", t = \"nagaram\"", "output" => "true"],
            ["input" => "s = \"rat\", t = \"car\"", "output" => "false"]
        ]),
        'test_cases' => json_encode([
            ["input" => "anagram\nnagaram", "output" => "true"],
            ["input" => "rat\ncar", "output" => "false"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def isAnagram(self, s: str, t: str) -> bool:\n        ",
            'javascript' => "/**\n * @param {string} s\n * @param {string} t\n * @return {boolean}\n */\nvar isAnagram = function(s, t) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    lines = sys.stdin.read().splitlines()\n    s, t = lines[0].strip(), lines[1].strip()\n    res = False\n    if 'Solution' in globals(): res = Solution().isAnagram(s, t)\n    elif 'isAnagram' in globals(): res = isAnagram(s, t)\n    print('true' if res else 'false')",
            'javascript' => "const fs = require('fs');\nconst lines = fs.readFileSync(0, 'utf-8').trim().split('\\n');\nconst s = lines[0].trim(); const t = lines[1].trim();\nlet res = (typeof Solution === 'function') ? new Solution().isAnagram(s, t) : isAnagram(s, t);\nconsole.log(res ? 'true' : 'false');"
        ])
    ],

    // --- TWO POINTERS ---
    [
        'title' => 'Container With Most Water',
        'slug' => 'container-with-most-water',
        'difficulty' => 'Medium',
        'topic' => 'Two Pointers',
        'description' => 'You are given an integer array height of length n. There are n vertical lines drawn such that the two endpoints of the ith line are (i, 0) and (i, height[i]). Find two lines that together with the x-axis form a container, such that the container contains the most water. Return the maximum amount of water a container can store.',
        'examples' => json_encode([
            ["input" => "height = [1,8,6,2,5,4,8,3,7]", "output" => "49", "explanation" => "Max area is between height 8 (index 1) and height 7 (index 8). Width = 7, Height = 7. Area = 49."],
            ["input" => "height = [1,1]", "output" => "1"]
        ]),
        'test_cases' => json_encode([
            ["input" => "1 8 6 2 5 4 8 3 7", "output" => "49"],
            ["input" => "1 1", "output" => "1"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def maxArea(self, height: List[int]) -> int:\n        ",
            'javascript' => "/**\n * @param {number[]} height\n * @return {number}\n */\nvar maxArea = function(height) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    height = list(map(int, sys.stdin.read().strip().split()))\n    if 'Solution' in globals(): print(Solution().maxArea(height))\n    elif 'maxArea' in globals(): print(maxArea(height))",
            'javascript' => "const fs = require('fs');\nconst height = fs.readFileSync(0, 'utf-8').trim().split(' ').map(Number);\nif (typeof Solution === 'function') console.log(new Solution().maxArea(height));\nelse console.log(maxArea(height));"
        ])
    ],

    // --- SLIDING WINDOW ---
    [
        'title' => 'Longest Repeating Character Replacement',
        'slug' => 'longest-repeating-character-replacement',
        'difficulty' => 'Medium',
        'topic' => 'Sliding Window',
        'description' => 'You are given a string s and an integer k. You can choose any character of the string and change it to any other uppercase English character. You can perform this operation at most k times. Return the length of the longest substring containing the same letter you can get after performing the above operations.',
        'examples' => json_encode([
            ["input" => "s = \"ABAB\", k = 2", "output" => "4", "explanation" => "Replace the two 'A's with two 'B's or vice versa."],
            ["input" => "s = \"AABABBA\", k = 1", "output" => "4"]
        ]),
        'test_cases' => json_encode([
            ["input" => "ABAB\n2", "output" => "4"],
            ["input" => "AABABBA\n1", "output" => "4"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def characterReplacement(self, s: str, k: int) -> int:\n        ",
            'javascript' => "/**\n * @param {string} s\n * @param {number} k\n * @return {number}\n */\nvar characterReplacement = function(s, k) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    lines = sys.stdin.read().splitlines()\n    s = lines[0].strip()\n    k = int(lines[1])\n    if 'Solution' in globals(): print(Solution().characterReplacement(s, k))\n    elif 'characterReplacement' in globals(): print(characterReplacement(s, k))",
            'javascript' => "const fs = require('fs');\nconst lines = fs.readFileSync(0, 'utf-8').trim().split('\\n');\nconst s = lines[0].trim();\nconst k = Number(lines[1]);\nlet res = (typeof Solution === 'function') ? new Solution().characterReplacement(s, k) : characterReplacement(s, k);\nconsole.log(res);"
        ])
    ],

    // --- SEARCHING ---
    [
        'title' => 'Binary Search',
        'slug' => 'binary-search',
        'difficulty' => 'Easy',
        'topic' => 'Binary Search',
        'description' => 'Given an array of integers `nums` which is sorted in ascending order, and an integer `target`, write a function to search `target` in `nums`. If `target` exists, then return its index. Otherwise, return -1.',
         'examples' => json_encode([
            ["input" => "nums = [-1,0,3,5,9,12], target = 9", "output" => "4"],
            ["input" => "nums = [-1,0,3,5,9,12], target = 2", "output" => "-1"]
        ]),
        'test_cases' => json_encode([
            ["input" => "-1 0 3 5 9 12\n9", "output" => "4"],
            ["input" => "-1 0 3 5 9 12\n2", "output" => "-1"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def search(self, nums: List[int], target: int) -> int:\n        ",
             'javascript' => "/**\n * @param {number[]} nums\n * @param {number} target\n * @return {number}\n */\nvar search = function(nums, target) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    lines = sys.stdin.read().splitlines()\n    nums = list(map(int, lines[0].split()))\n    target = int(lines[1])\n    if 'Solution' in globals(): print(Solution().search(nums, target))\n    elif 'search' in globals(): print(search(nums, target))",
            'javascript' => "const fs = require('fs');\nconst lines = fs.readFileSync(0, 'utf-8').trim().split('\\n');\nconst nums = lines[0].trim().split(' ').map(Number);\nconst target = Number(lines[1]);\nlet res = (typeof Solution === 'function') ? new Solution().search(nums, target) : search(nums, target);\nconsole.log(res);"
        ])
    ],
    [
        'title' => 'Search in Rotated Sorted Array',
        'slug' => 'search-in-rotated-sorted-array',
        'difficulty' => 'Medium',
        'topic' => 'Binary Search',
        'description' => 'There is an integer array `nums` sorted in ascending order (with distinct values). Prior to being passed to your function, `nums` is possibly rotated at an unknown pivot. Given the array `nums` and an integer `target`, return the index of `target` if it is in `nums`, or -1 if it is not.',
         'examples' => json_encode([
            ["input" => "nums = [4,5,6,7,0,1,2], target = 0", "output" => "4"],
            ["input" => "nums = [4,5,6,7,0,1,2], target = 3", "output" => "-1"]
        ]),
        'test_cases' => json_encode([
            ["input" => "4 5 6 7 0 1 2\n0", "output" => "4"],
            ["input" => "4 5 6 7 0 1 2\n3", "output" => "-1"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def search(self, nums: List[int], target: int) -> int:\n        ",
             'javascript' => "/**\n * @param {number[]} nums\n * @param {number} target\n * @return {number}\n */\nvar search = function(nums, target) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    lines = sys.stdin.read().splitlines()\n    nums = list(map(int, lines[0].split()))\n    target = int(lines[1])\n    if 'Solution' in globals(): print(Solution().search(nums, target))\n    elif 'search' in globals(): print(search(nums, target))",
            'javascript' => "const fs = require('fs');\nconst lines = fs.readFileSync(0, 'utf-8').trim().split('\\n');\nconst nums = lines[0].trim().split(' ').map(Number);\nconst target = Number(lines[1]);\nlet res = (typeof Solution === 'function') ? new Solution().search(nums, target) : search(nums, target);\nconsole.log(res);"
        ])
    ],

    // --- RECURSION / DP ---
    [
        'title' => 'Climbing Stairs',
        'slug' => 'climbing-stairs',
        'difficulty' => 'Easy',
        'topic' => 'Dynamic Programming',
        'description' => 'You are climbing a staircase. It takes `n` steps to reach the top. Each time you can either climb 1 or 2 steps. In how many distinct ways can you climb to the top?',
        'examples' => json_encode([
            ["input" => "n = 2", "output" => "2"],
            ["input" => "n = 3", "output" => "3"]
        ]),
        'test_cases' => json_encode([
            ["input" => "2", "output" => "2"],
            ["input" => "5", "output" => "8"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def climbStairs(self, n: int) -> int:\n        ",
            'javascript' => "/**\n * @param {number} n\n * @return {number}\n */\nvar climbStairs = function(n) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    n = int(sys.stdin.read().strip())\n    if 'Solution' in globals(): print(Solution().climbStairs(n))\n    elif 'climbStairs' in globals(): print(climbStairs(n))",
            'javascript' => "const fs = require('fs');\nconst n = Number(fs.readFileSync(0, 'utf-8').trim());\nif (typeof Solution === 'function') console.log(new Solution().climbStairs(n));\nelse console.log(climbStairs(n));"
        ])
    ],
    [
        'title' => 'Fibonacci Number',
        'slug' => 'fibonacci-number',
        'difficulty' => 'Easy',
        'topic' => 'Dynamic Programming',
        'description' => 'The Fibonacci numbers, commonly denoted F(n) form a sequence, called the Fibonacci sequence, such that each number is the sum of the two preceding ones, starting from 0 and 1. Given `n`, calculate `F(n)`.',
        'examples' => json_encode([
            ["input" => "n = 2", "output" => "1"],
            ["input" => "n = 3", "output" => "2"]
        ]),
        'test_cases' => json_encode([
            ["input" => "2", "output" => "1"],
            ["input" => "10", "output" => "55"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def fib(self, n: int) -> int:\n        ",
            'javascript' => "/**\n * @param {number} n\n * @return {number}\n */\nvar fib = function(n) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    n = int(sys.stdin.read().strip())\n    if 'Solution' in globals(): print(Solution().fib(n))\n    elif 'fib' in globals(): print(fib(n))",
            'javascript' => "const fs = require('fs');\nconst n = Number(fs.readFileSync(0, 'utf-8').trim());\nif (typeof Solution === 'function') console.log(new Solution().fib(n));\nelse console.log(fib(n));"
        ])
    ],
    [
        'title' => 'Coin Change',
        'slug' => 'coin-change',
        'difficulty' => 'Medium',
        'topic' => 'Dynamic Programming',
        'description' => 'You are given an integer array `coins` representing coins of different denominations and an integer `amount` representing a total amount of money. Return the fewest number of coins that you need to make up that amount.',
        'examples' => json_encode([
            ["input" => "coins = [1,2,5], amount = 11", "output" => "3"],
            ["input" => "coins = [2], amount = 3", "output" => "-1"]
        ]),
        'test_cases' => json_encode([
            ["input" => "1 2 5\n11", "output" => "3"],
            ["input" => "2\n3", "output" => "-1"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def coinChange(self, coins: List[int], amount: int) -> int:\n        ",
            'javascript' => "/**\n * @param {number[]} coins\n * @param {number} amount\n * @return {number}\n */\nvar coinChange = function(coins, amount) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    lines = sys.stdin.read().splitlines()\n    coins = list(map(int, lines[0].split()))\n    amount = int(lines[1])\n    if 'Solution' in globals(): print(Solution().coinChange(coins, amount))\n    elif 'coinChange' in globals(): print(coinChange(coins, amount))",
            'javascript' => "const fs = require('fs');\nconst lines = fs.readFileSync(0, 'utf-8').trim().split('\\n');\nconst coins = lines[0].trim().split(' ').map(Number);\nconst amount = Number(lines[1]);\nlet res = (typeof Solution === 'function') ? new Solution().coinChange(coins, amount) : coinChange(coins, amount);\nconsole.log(res);"
        ])
    ],

    // --- SORTING ---
    [
        'title' => 'Sort Colors',
        'slug' => 'sort-colors',
        'difficulty' => 'Medium',
        'topic' => 'Sorting',
        'description' => 'Given an array `nums` with `n` objects colored red, white, or blue, sort them in-place so that objects of the same color are adjacent, with the colors in the order red, white, and blue. We will use the integers 0, 1, and 2 to represent the color red, white, and blue, respectively.',
        'examples' => json_encode([
            ["input" => "nums = [2,0,2,1,1,0]", "output" => "[0,0,1,1,2,2]"],
            ["input" => "nums = [2,0,1]", "output" => "[0,1,2]"]
        ]),
        'test_cases' => json_encode([
            ["input" => "2 0 2 1 1 0", "output" => "0 0 1 1 2 2"],
            ["input" => "2 0 1", "output" => "0 1 2"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def sortColors(self, nums: List[int]) -> None:\n        \"\"\"\n        Do not return anything, modify nums in-place instead.\n        \"\"\"\n        ",
            'javascript' => "/**\n * @param {number[]} nums\n * @return {void} Do not return anything, modify nums in-place instead.\n */\nvar sortColors = function(nums) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    nums = list(map(int, sys.stdin.read().strip().split()))\n    if 'Solution' in globals(): Solution().sortColors(nums)\n    elif 'sortColors' in globals(): sortColors(nums)\n    print(' '.join(map(str, nums)))",
            'javascript' => "const fs = require('fs');\nconst nums = fs.readFileSync(0, 'utf-8').trim().split(' ').map(Number);\nif (typeof Solution === 'function') new Solution().sortColors(nums);\nelse sortColors(nums);\nconsole.log(nums.join(' '));"
        ])
    ],

    // --- LINKED LIST (Simple Driver) ---
    [
        'title' => 'Reverse Linked List',
        'slug' => 'reverse-linked-list',
        'difficulty' => 'Easy',
        'topic' => 'Linked List',
        'description' => 'Given the `head` of a singly linked list, reverse the list, and return the reversed list. (Note: For this platform, input/output is represented as arrays).',
        'examples' => json_encode([
            ["input" => "head = [1,2,3,4,5]", "output" => "[5,4,3,2,1]"],
            ["input" => "head = [1,2]", "output" => "[2,1]"]
        ]),
        'test_cases' => json_encode([
            ["input" => "1 2 3 4 5", "output" => "5 4 3 2 1"],
            ["input" => "1 2", "output" => "2 1"]
        ]),
        'starter_code' => json_encode([
            'python' => "# Definition for singly-linked list.\n# class ListNode:\n#     def __init__(self, val=0, next=None):\n#         self.val = val\n#         self.next = next\nclass Solution:\n    def reverseList(self, head: Optional[ListNode]) -> Optional[ListNode]:\n        ",
            'javascript' => "/**\n * Definition for singly-linked list.\n * function ListNode(val, next) {\n *     this.val = (val===undefined ? 0 : val)\n *     this.next = (next===undefined ? null : next)\n * }\n */\n/**\n * @param {ListNode} head\n * @return {ListNode}\n */\nvar reverseList = function(head) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nclass ListNode:\n    def __init__(self, val=0, next=None):\n        self.val = val\n        self.next = next\ndef to_list(head): \n    arr = []\n    while head: arr.append(str(head.val)); head = head.next\n    return ' '.join(arr)\nif __name__ == '__main__':\n    inp = sys.stdin.read().strip()\n    if not inp: print(''); sys.exit(0)\n    vals = list(map(int, inp.split()))\n    dummy = ListNode(0)\n    curr = dummy\n    for v in vals: curr.next = ListNode(v); curr = curr.next\n    head = dummy.next\n    if 'Solution' in globals(): head = Solution().reverseList(head)\n    elif 'reverseList' in globals(): head = reverseList(head)\n    print(to_list(head))",
            'javascript' => "const fs = require('fs');\nfunction ListNode(val, next) { this.val = (val===undefined ? 0 : val); this.next = (next===undefined ? null : next); }\nfunction toList(head) { const arr = []; while(head) { arr.push(head.val); head = head.next; } return arr.join(' '); }\nconst inp = fs.readFileSync(0, 'utf-8').trim();\nif (!inp) { console.log(''); process.exit(0); }\nconst vals = inp.split(' ').map(Number);\nconst dummy = new ListNode(0);\nlet curr = dummy;\nfor (const v of vals) { curr.next = new ListNode(v); curr = curr.next; }\nlet head = dummy.next;\nif (typeof Solution === 'function') head = new Solution().reverseList(head);\nelse head = reverseList(head);\nconsole.log(toList(head));"
        ])
    ]
];
?>
