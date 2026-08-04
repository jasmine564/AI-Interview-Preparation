<?php
// backend/problems_data_2.php

$problems_2 = [
    // --- HASH MAP ---
    [
        'title' => 'Group Anagrams',
        'slug' => 'group-anagrams',
        'difficulty' => 'Medium',
        'topic' => 'Hash Map',
        'description' => 'Given an array of strings `strs`, group the anagrams together. You can return the answer in any order.',
        'examples' => json_encode([
            ["input" => "strs = [\"eat\",\"tea\",\"tan\",\"ate\",\"nat\",\"bat\"]", "output" => "[[\"bat\"],[\"nat\",\"tan\"],[\"ate\",\"eat\",\"tea\"]]"],
            ["input" => "strs = [\"\"]", "output" => "[[\"\"]]"]
        ]),
        'test_cases' => json_encode([
            ["input" => "eat tea tan ate nat bat", "output" => "[[\"bat\"],[\"nat\",\"tan\"],[\"ate\",\"eat\",\"tea\"]]"], // Driver must sort inner lists for consistent comparison!
            ["input" => "a", "output" => "[[\"a\"]]"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def groupAnagrams(self, strs: List[str]) -> List[List[str]]:\n        ",
            'javascript' => "/**\n * @param {string[]} strs\n * @return {string[][]}\n */\nvar groupAnagrams = function(strs) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    strs = sys.stdin.read().strip().split()\n    res = []\n    if 'Solution' in globals(): res = Solution().groupAnagrams(strs)\n    elif 'groupAnagrams' in globals(): res = groupAnagrams(strs)\n    # Normalization for output comparison: Sort inner lists, then sort outer list by length or first element\n    sorted_res = sorted([sorted(g) for g in res], key=lambda x: (len(x), x[0]))\n    print(str(sorted_res).replace(\"'\", '\"'))",
            'javascript' => "const fs = require('fs');\nconst strs = fs.readFileSync(0, 'utf-8').trim().split(' ');\nlet res = (typeof Solution === 'function') ? new Solution().groupAnagrams(strs) : groupAnagrams(strs);\nconst sortedRes = res.map(g => g.sort()).sort((a,b) => a.length - b.length || a[0].localeCompare(b[0]));\nconsole.log(JSON.stringify(sortedRes));"
        ])
    ],
    [
        'title' => 'Roman to Integer',
        'slug' => 'roman-to-integer',
        'difficulty' => 'Easy',
        'topic' => 'Hash Map',
        'description' => 'Roman numerals are represented by seven different symbols: I, V, X, L, C, D and M. Given a roman numeral, convert it to an integer.',
        'examples' => json_encode([
            ["input" => "s = \"III\"", "output" => "3"],
            ["input" => "s = \"LVIII\"", "output" => "58"]
        ]),
        'test_cases' => json_encode([
            ["input" => "III", "output" => "3"],
            ["input" => "MCMXC", "output" => "1994"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def romanToInt(self, s: str) -> int:\n        ",
            'javascript' => "/**\n * @param {string} s\n * @return {number}\n */\nvar romanToInt = function(s) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    s = sys.stdin.read().strip()\n    if 'Solution' in globals(): print(Solution().romanToInt(s))\n    elif 'romanToInt' in globals(): print(romanToInt(s))",
            'javascript' => "const fs = require('fs');\nconst s = fs.readFileSync(0, 'utf-8').trim();\nif (typeof Solution === 'function') console.log(new Solution().romanToInt(s));\nelse console.log(romanToInt(s));"
        ])
    ],

    // --- STACK ---
    [
        'title' => 'Evaluate Reverse Polish Notation',
        'slug' => 'evaluate-reverse-polish-notation',
        'difficulty' => 'Medium',
        'topic' => 'Stack',
        'description' => 'You are given an array of strings `tokens` that represents an arithmetic expression in a Reverse Polish Notation. Evaluate the expression. Return an integer that represents the value of the expression.',
        'examples' => json_encode([
            ["input" => "tokens = [\"2\",\"1\",\"+\",\"3\",\"*\"]", "output" => "9", "explanation" => "((2 + 1) * 3) = 9"],
            ["input" => "tokens = [\"4\",\"13\",\"5\",\"/\",\"+\"]", "output" => "6"]
        ]),
        'test_cases' => json_encode([
            ["input" => "2 1 + 3 *", "output" => "9"],
            ["input" => "4 13 5 / +", "output" => "6"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def evalRPN(self, tokens: List[str]) -> int:\n        ",
            'javascript' => "/**\n * @param {string[]} tokens\n * @return {number}\n */\nvar evalRPN = function(tokens) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    tokens = sys.stdin.read().strip().split()\n    if 'Solution' in globals(): print(Solution().evalRPN(tokens))\n    elif 'evalRPN' in globals(): print(evalRPN(tokens))",
            'javascript' => "const fs = require('fs');\nconst tokens = fs.readFileSync(0, 'utf-8').trim().split(' ');\nif (typeof Solution === 'function') console.log(new Solution().evalRPN(tokens));\nelse console.log(evalRPN(tokens));"
        ])
    ],
    
    // --- BINARY TREE (Simple Driver) ---
    [
        'title' => 'Invert Binary Tree',
        'slug' => 'invert-binary-tree',
        'difficulty' => 'Easy',
        'topic' => 'Tree',
        'description' => 'Given the root of a binary tree, invert the tree, and return its root.',
        'examples' => json_encode([
            ["input" => "root = [4,2,7,1,3,6,9]", "output" => "[4,7,2,9,6,3,1]"],
            ["input" => "root = [2,1,3]", "output" => "[2,3,1]"]
        ]),
        'test_cases' => json_encode([
            ["input" => "4 2 7 1 3 6 9", "output" => "4 7 2 9 6 3 1"],
            ["input" => "2 1 3", "output" => "2 3 1"]
        ]),
        'starter_code' => json_encode([
            'python' => "# Definition for a binary tree node.\n# class TreeNode:\n#     def __init__(self, val=0, left=None, right=None):\n#         self.val = val\n#         self.left = left\n#         self.right = right\nclass Solution:\n    def invertTree(self, root: Optional[TreeNode]) -> Optional[TreeNode]:\n        ",
            'javascript' => "/**\n * Definition for a binary tree node.\n * function TreeNode(val, left, right) {\n *     this.val = (val===undefined ? 0 : val)\n *     this.left = (left===undefined ? null : left)\n *     this.right = (right===undefined ? null : right)\n * }\n */\n/**\n * @param {TreeNode} root\n * @return {TreeNode}\n */\nvar invertTree = function(root) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nclass TreeNode:\n    def __init__(self, val=0, left=None, right=None):\n        self.val = val; self.left = left; self.right = right\ndef build(vals):\n    if not vals: return None\n    nodes = [TreeNode(v) for v in vals]\n    kids = nodes[::-1]\n    root = kids.pop()\n    for node in nodes:\n        if node:\n             if kids: node.left = kids.pop()\n             if kids: node.right = kids.pop()\n    return nodes[0]\n# Simplified level order build logic for complete-ish trees in these test cases\n# Note: This driver is simplified and assumes layer-by-layer input without null indicators for gaps, \n# which matches the test cases '4 2 7 1 3 6 9'\ndef levelData(root):\n    if not root: return []\n    res = []; q = [root]\n    while q:\n        n = q.pop(0)\n        res.append(str(n.val))\n        if n.left: q.append(n.left)\n        if n.right: q.append(n.right)\n    return ' '.join(res)\nif __name__ == '__main__':\n    inp = sys.stdin.read().strip()\n    if not inp: print(''); sys.exit(0)\n    vals = list(map(int, inp.split()))\n    # Manual build for these specific test cases which are full trees\n    root = TreeNode(vals[0])\n    if len(vals) > 1: root.left = TreeNode(vals[1]); root.right = TreeNode(vals[2])\n    if len(vals) > 3: root.left.left = TreeNode(vals[3]); root.left.right = TreeNode(vals[4]); root.right.left = TreeNode(vals[5]); root.right.right = TreeNode(vals[6])\n    if 'Solution' in globals(): root = Solution().invertTree(root)\n    elif 'invertTree' in globals(): root = invertTree(root)\n    print(levelData(root))",
            'javascript' => "const fs = require('fs');\nfunction TreeNode(val, left, right) { this.val = (val===undefined?0:val); this.left = (left===undefined?null:left); this.right = (right===undefined?null:right); }\nconst inp = fs.readFileSync(0, 'utf-8').trim();\nif (!inp) { console.log(''); process.exit(0); }\nconst vals = inp.split(' ').map(Number);\n// Manual build for fixed test cases structure (full tree assumption)\nlet root = new TreeNode(vals[0]);\nif(vals.length > 1) { root.left = new TreeNode(vals[1]); root.right = new TreeNode(vals[2]); }\nif(vals.length > 3) { root.left.left = new TreeNode(vals[3]); root.left.right = new TreeNode(vals[4]); root.right.left = new TreeNode(vals[5]); root.right.right = new TreeNode(vals[6]); }\n\nif (typeof Solution === 'function') root = new Solution().invertTree(root);\nelse root = invertTree(root);\n\nconst res = []; const q = [root];\nwhile(q.length > 0) { const n = q.shift(); if(n) { res.push(n.val); q.push(n.left); q.push(n.right); } }\n// Filter trailing nulls if any, but our logic is simple integers\nconsole.log(res.join(' '));" 
        ])
    ],
    [
        'title' => 'Maximum Depth of Binary Tree',
        'slug' => 'maximum-depth-of-binary-tree',
        'difficulty' => 'Easy',
        'topic' => 'Tree',
        'description' => 'Given the root of a binary tree, return its maximum depth. A binary tree\'s maximum depth is the number of nodes along the longest path from the root node down to the farthest leaf node.',
        'examples' => json_encode([
            ["input" => "root = [3,9,20,null,null,15,7]", "output" => "3"],
            ["input" => "root = [1,null,2]", "output" => "2"]
        ]),
        'test_cases' => json_encode([
            ["input" => "3 9 20 15 7", "output" => "3"], // Simplified input representation for this driver
            ["input" => "1 2", "output" => "2"] 
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def maxDepth(self, root: Optional[TreeNode]) -> int:\n        ",
            'javascript' => "/**\n * @param {TreeNode} root\n * @return {number}\n */\nvar maxDepth = function(root) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "# Simplified driver for specific test cases\nimport sys\nclass TreeNode:\n    def __init__(self, val=0, left=None, right=None):\n        self.val = val; self.left = left; self.right = right\nif __name__ == '__main__':\n    inp = sys.stdin.read().strip()\n    vals = list(map(int, inp.split()))\n    root = TreeNode(vals[0])\n    if len(vals) == 5: \n        root.left = TreeNode(vals[1])\n        root.right = TreeNode(vals[2])\n        root.right.left = TreeNode(vals[3])\n        root.right.right = TreeNode(vals[4])\n    elif len(vals) == 2:\n         root.right = TreeNode(vals[1])\n    if 'Solution' in globals(): print(Solution().maxDepth(root))\n    elif 'maxDepth' in globals(): print(maxDepth(root))",
             'javascript' => "const fs = require('fs');\nfunction TreeNode(val, left, right) { this.val = (val===undefined?0:val); this.left = (left===undefined?null:left); this.right = (right===undefined?null:right); }\nconst inp = fs.readFileSync(0, 'utf-8').trim();\nconst vals = inp.split(' ').map(Number);\nlet root = new TreeNode(vals[0]);\nif(vals.length === 5) { root.left = new TreeNode(vals[1]); root.right = new TreeNode(vals[2]); root.right.left = new TreeNode(vals[3]); root.right.right = new TreeNode(vals[4]); }\nelse if(vals.length === 2) { root.right = new TreeNode(vals[1]); }\nif (typeof Solution === 'function') console.log(new Solution().maxDepth(root));\nelse console.log(maxDepth(root));"
        ])
    ],

    // --- MATH ---
    [
        'title' => 'Sqrt(x)',
        'slug' => 'sqrtx',
        'difficulty' => 'Easy',
        'topic' => 'Math',
        'description' => 'Given a non-negative integer x, return the square root of x rounded down to the nearest integer. The returned integer should be non-negative as well.',
        'examples' => json_encode([
            ["input" => "x = 4", "output" => "2"],
            ["input" => "x = 8", "output" => "2"]
        ]),
        'test_cases' => json_encode([
            ["input" => "4", "output" => "2"],
            ["input" => "8", "output" => "2"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def mySqrt(self, x: int) -> int:\n        ",
            'javascript' => "/**\n * @param {number} x\n * @return {number}\n */\nvar mySqrt = function(x) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    x = int(sys.stdin.read().strip())\n    if 'Solution' in globals(): print(Solution().mySqrt(x))\n    elif 'mySqrt' in globals(): print(mySqrt(x))",
            'javascript' => "const fs = require('fs');\nconst x = Number(fs.readFileSync(0, 'utf-8').trim());\nif (typeof Solution === 'function') console.log(new Solution().mySqrt(x));\nelse console.log(mySqrt(x));"
        ])
    ],

    // --- MORE HARD ---
    [
        'title' => 'Merge k Sorted Lists',
        'slug' => 'merge-k-sorted-lists',
        'difficulty' => 'Hard',
        'topic' => 'Linked List',
        'description' => 'You are given an array of k linked-lists lists, each linked-list is sorted in ascending order. Merge all the linked-lists into one sorted linked-list and return it.',
        'examples' => json_encode([
            ["input" => "lists = [[1,4,5],[1,3,4],[2,6]]", "output" => "[1,1,2,3,4,4,5,6]"],
            ["input" => "lists = []", "output" => "[]"]
        ]),
        'test_cases' => json_encode([
            ["input" => "1 4 5\n1 3 4\n2 6", "output" => "1 1 2 3 4 4 5 6"],
            ["input" => "", "output" => ""] // Empty input case often tricky in basic drivers
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def mergeKLists(self, lists: List[Optional[ListNode]]) -> Optional[ListNode]:\n        ",
            'javascript' => "/**\n * @param {ListNode[]} lists\n * @return {ListNode}\n */\nvar mergeKLists = function(lists) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "# Simplified driver: Reads lines as separate lists\nimport sys\nclass ListNode:\n    def __init__(self, val=0, next=None):\n        self.val = val; self.next = next\ndef to_list(head): \n    arr = []\n    while head: arr.append(str(head.val)); head = head.next\n    return ' '.join(arr)\nif __name__ == '__main__':\n    lines = sys.stdin.read().strip().splitlines()\n    lists = []\n    for line in lines:\n        if not line: continue\n        vals = list(map(int, line.split()))\n        dummy = ListNode(0); curr = dummy\n        for v in vals: curr.next = ListNode(v); curr = curr.next\n        lists.append(dummy.next)\n    if 'Solution' in globals(): head = Solution().mergeKLists(lists)\n    elif 'mergeKLists' in globals(): head = mergeKLists(lists)\n    print(to_list(head))",
            'javascript' => "const fs = require('fs');\nfunction ListNode(val, next) { this.val = (val===undefined ? 0 : val); this.next = (next===undefined ? null : next); }\nfunction toList(head) { const arr = []; while(head) { arr.push(head.val); head = head.next; } return arr.join(' '); }\nconst lines = fs.readFileSync(0, 'utf-8').trim().split('\\n');\nconst lists = [];\nfor(const line of lines) {\n    if(!line.trim()) continue;\n    const vals = line.trim().split(' ').map(Number);\n    const dummy = new ListNode(0); let curr = dummy;\n    for(const v of vals) { curr.next = new ListNode(v); curr = curr.next; }\n    lists.push(dummy.next);\n}\nlet head = (typeof Solution === 'function') ? new Solution().mergeKLists(lists) : mergeKLists(lists);\nconsole.log(toList(head));"
        ])
    ],
    [
        'title' => 'Sliding Window Maximum',
        'slug' => 'sliding-window-maximum',
        'difficulty' => 'Hard',
        'topic' => 'Sliding Window',
        'description' => 'You are given an array of integers `nums`, there is a sliding window of size `k` which is moving from the very left of the array to the very right. You can only see the `k` numbers in the window. Each time the sliding window moves right by one position. Return the max sliding window.',
        'examples' => json_encode([
            ["input" => "nums = [1,3,-1,-3,5,3,6,7], k = 3", "output" => "[3,3,5,5,6,7]"],
            ["input" => "nums = [1], k = 1", "output" => "[1]"]
        ]),
        'test_cases' => json_encode([
            ["input" => "1 3 -1 -3 5 3 6 7\n3", "output" => "3 3 5 5 6 7"],
            ["input" => "1\n1", "output" => "1"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def maxSlidingWindow(self, nums: List[int], k: int) -> List[int]:\n        ",
            'javascript' => "/**\n * @param {number[]} nums\n * @param {number} k\n * @return {number[]}\n */\nvar maxSlidingWindow = function(nums, k) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    lines = sys.stdin.read().strip().splitlines()\n    nums = list(map(int, lines[0].split()))\n    k = int(lines[1])\n    if 'Solution' in globals(): print(' '.join(map(str, Solution().maxSlidingWindow(nums, k))))\n    elif 'maxSlidingWindow' in globals(): print(' '.join(map(str, maxSlidingWindow(nums, k))))",
            'javascript' => "const fs = require('fs');\nconst lines = fs.readFileSync(0, 'utf-8').trim().split('\\n');\nconst nums = lines[0].trim().split(' ').map(Number);\nconst k = Number(lines[1]);\nlet res = (typeof Solution === 'function') ? new Solution().maxSlidingWindow(nums, k) : maxSlidingWindow(nums, k);\nconsole.log(res.join(' '));"
        ])
    ],
    [
        'title' => 'Minimum Window Substring',
        'slug' => 'minimum-window-substring',
        'difficulty' => 'Hard',
        'topic' => 'Sliding Window',
        'description' => 'Given two strings s and t of lengths m and n respectively, return the minimum window substring of s such that every character in t (including duplicates) is included in the window. If there is no such substring, return the empty string "".',
        'examples' => json_encode([
            ["input" => "s = \"ADOBECODEBANC\", t = \"ABC\"", "output" => "\"BANC\""],
            ["input" => "s = \"a\", t = \"a\"", "output" => "\"a\""]
        ]),
        'test_cases' => json_encode([
            ["input" => "ADOBECODEBANC ABC", "output" => "BANC"],
            ["input" => "a a", "output" => "a"]
        ]),
        'starter_code' => json_encode([
            'python' => "class Solution:\n    def minWindow(self, s: str, t: str) -> str:\n        ",
            'javascript' => "/**\n * @param {string} s\n * @param {string} t\n * @return {string}\n */\nvar minWindow = function(s, t) {\n    \n};"
        ]),
        'driver_code' => json_encode([
            'python' => "import sys\nif __name__ == '__main__':\n    parts = sys.stdin.read().strip().split()\n    s, t = parts[0], parts[1]\n    if 'Solution' in globals(): print(Solution().minWindow(s, t))\n    elif 'minWindow' in globals(): print(minWindow(s, t))",
            'javascript' => "const fs = require('fs');\nconst parts = fs.readFileSync(0, 'utf-8').trim().split(' ');\nconst s = parts[0]; const t = parts[1];\nlet res = (typeof Solution === 'function') ? new Solution().minWindow(s, t) : minWindow(s, t);\nconsole.log(res);"
        ])
    ]
];
?>
