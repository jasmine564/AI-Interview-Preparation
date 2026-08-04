
export const JOB_ROLES = [
    "Custom Job Description",
    "Business Analyst",
    "Product Manager",
    "Software Engineer",
    "Marketing Specialist",
    "Data Analyst",
    "Customer Service Representative",
    "Sales Representative",
    "Human Resources Specialist",
    "UX/UI Designer",
    "QA Engineer"
];

export const JOB_DESCRIPTIONS: Record<string, string> = {
    "Business Analyst": `Job Title: Business Analyst
  
Summary: We are looking for a detail-oriented Business Analyst to bridge the gap between IT and business using data analytics to assess processes, determine requirements, and deliver data-driven recommendations and reports to executives and stakeholders.

Responsibilities:
- Engage with business leaders and users to understand how data-driven changes to process, products, services, software and hardware can improve efficiencies and add value.
- Articulate those ideas but also balance them against what’s technologically feasible and financially and functionally reasonable.
- Create detailed business analysis, outlining problems, opportunities and solutions for a business.

Requirements:
- Proven experience as a Business Analyst or relevant role.
- Experience in data analysis and visualization methods.
- Knowledge of business intelligence tools (e.g., Tableau, Power BI).
- Strong communication and presentation skills.`,

    "Product Manager": `Job Title: Product Manager

Summary: We are seeking an experienced Product Manager to direct product development and ensure high return on investment (ROI). You’ll translate ideas into strategy and features, and follow product development from start to finish.

Responsibilities:
- Develop and implement product strategies consistent with company vision.
- Collect and analyze feedback from customers, stakeholders and other teams to shape requirements, features and end products.
- Work with senior management to create product plans and roadmaps.

Requirements:
- Proven experience as a Product Manager or similar role.
- Experience in product lifecycle management.
- Background in software development and program management is preferred.
- Familiarity with Agile framework.`,

    "Software Engineer": `Job Title: Software Engineer

Summary: We are looking for a passionate Software Engineer to design, develop and install software solutions.

Responsibilities:
- Execute full lifecycle software development.
- Write well-designed, testable, efficient code.
- Produce specifications and determine operational feasibility.
- Integrate software components into a fully functional software system.

Requirements:
- BSc degree in Computer Science, Engineering or relevant field.
- Proven work experience as a Software Engineer or Software Developer.
- Experience designing interactive applications.
- Ability to develop software in C++, Java, Python or other programming languages.`,

    "Marketing Specialist": `Job Title: Marketing Specialist

Summary: We are looking for an enthusiastic Marketing Specialist to help us in our overall marketing efforts. You will be an integral part of the development and execution of marketing plans to reach targets from brand awareness to product promotion.

Responsibilities:
- Conduct market research to find answers about consumer requirements, habits and trends.
- Brainstorm and develop ideas for creative marketing campaigns.
- Assist in analyze marketing data (campaign results, conversion rates, traffic etc.) to help shape future marketing strategies.

Requirements:
- Proven experience as a Marketing Specialist or similar role.
- Thorough understanding of marketing elements (interaction with traditional and new methods).
- Solid computer skills, including MS Office, marketing software (Adobe Creative Suite & CRM) and applications (Web analytics, Google Adwords etc.).
- Well-organized and detail oriented.`,

    "Data Analyst": `Job Title: Data Analyst

Summary: We are looking for a passionate Data Analyst. The successful candidate will turn data into information, information into insight and insight into business decisions.

Responsibilities:
- Interpret data, analyze results using statistical techniques and provide ongoing reports.
- Develop and implement databases, data collection systems, data analytics and other strategies that optimize statistical efficiency and quality.
- Acquire data from primary or secondary data sources and maintain databases/data systems.

Requirements:
- Proven working experience as a Data Analyst or Business Data Analyst.
- Technical expertise regarding data models, database design development, data mining and segmentation techniques.
- Strong knowledge of and experience with reporting packages (Business Objects etc), databases (SQL etc), programming (XML, Javascript, or ETL frameworks).
- Knowledge of statistics and experience using statistical packages for analyzing datasets.`,

    "Customer Service Representative": `Job Title: Customer Service Representative

Summary: We are looking for a customer-oriented service representative. A customer service representative, or CSR, will act as a liaison, provide product/services information and resolve any emerging problems that our customer accounts might face with accuracy and efficiency.

Responsibilities:
- Manage large amounts of incoming phone calls.
- Generate sales leads.
- Identify and assess customers’ needs to achieve satisfaction.

Requirements:
- Proven customer support experience or experience as a Client Service Representative.
- Track record of over-achieving quota.
- Strong phone contact handling skills and active listening.
- Familiarity with CRM systems and practices.`,

    "Sales Representative": `Job Title: Sales Representative

Summary: We are looking for a results-driven Sales Representative to actively seek out and engage customer prospects. You will provide complete and appropriate solutions for every customer in order to boost top-line revenue growth, customer acquisition levels and profitability.

Responsibilities:
- Present, promote and sell products/services using solid arguments to existing and prospective customers.
- Perform cost-benefit and needs analysis of existing/potential customers to meet their needs.
- Establish, develop and maintain positive business and customer relationships.

Requirements:
- Proven work experience as a Sales Representative.
- Excellent knowledge of MS Office.
- Highly motivated and target driven with a proven track record in sales.
- Excellent selling, communication and negotiation skills.`,

    "Human Resources Specialist": `Job Title: Human Resources Specialist

Summary: We are looking for an HR Specialist to join our team and monitor all Human Resources functions.

Responsibilities:
- Prepare and review compensation and benefits packages.
- Administer health and life insurance programs.
- Implement training and development plans.
- Plan quarterly and annual performance review sessions.

Requirements:
- Proven work experience as an HR Specialist or HR Generalist.
- Hands-on experience with Human Resources Information Systems (HRIS).
- Knowledge of Applicant Tracking Systems (ATS).
- Solid understanding of labor legislation and payroll process.`,

    "UX/UI Designer": `Job Title: UX/UI Designer

Summary: We are looking for a UI/UX Designer to turn our software into easy-to-use products for our clients.

Responsibilities:
- Gather and evaluate user requirements in collaboration with product managers and engineers.
- Illustrate design ideas using storyboards, process flows and sitemaps.
- Design graphic user interface elements, like menus, tabs and widgets.

Requirements:
- Proven work experience as a UI/UX Designer or similar role.
- Portfolio of design projects.
- Knowledge of wireframe tools (e.g. Wireframe.cc and InVision).
- Up-to-date knowledge of design software like Adobe Illustrator and Photoshop.`,

    "QA Engineer": `Job Title: QA Engineer

Summary: We are looking for a Quality Assurance (QA) engineer to develop and execute exploratory and automated tests to ensure product quality.

Responsibilities:
- Review requirements, specifications and technical design documents to provide timely and meaningful feedback.
- Create detailed, comprehensive and well-structured test plans and test cases.
- Estimate, prioritize, plan and coordinate testing activities.

Requirements:
- Proven work experience in software development and software quality assurance.
- Strong knowledge of software QA methodologies, tools and processes.
- Experience in writing clear, concise and comprehensive test plans and test cases.
- Hands-on experience with automated testing tools.`,
};

export const JOB_QUESTIONS: Record<string, Array<{ id: number, text: string }>> = {
    "Business Analyst": [
        { id: 1, text: "Can you describe a time when you analyzed business data to identify a problem or opportunity? How did your insights influence the decision-making process?" },
        { id: 2, text: "How do you handle requirements gathering when stakeholders have conflicting needs?" },
        { id: 3, text: "Describe a situation where you had to explain a complex technical concept to a non-technical audience." },
        { id: 4, text: "Tell me about a time you identified a process inefficiency. What solution did you propose and what was the outcome?" }
    ],
    "Product Manager": [
        { id: 1, text: "Can you describe a time when you had to prioritize features for a product? How did you decide what was most important?" },
        { id: 2, text: "Tell me about a product you launched. What metrics did you use to measure its success?" },
        { id: 3, text: "How do you handle negative feedback from customers about a feature you designed?" },
        { id: 4, text: "Describe a time you had to pivot your product strategy based on market changes." }
    ],
    "Software Engineer": [
        { id: 1, text: "Tell me about a challenging bug you faced. How did you debug and resolve it?" },
        { id: 2, text: "Describe a time you had to optimize a piece of code for better performance." },
        { id: 3, text: "How do you handle disagreements with other engineers during code reviews?" },
        { id: 4, text: "Tell me about a project where you had to learn a new technology quickly." }
    ],
    "Marketing Specialist": [
        { id: 1, text: "Describe a successful marketing campaign you managed. What made it successful?" },
        { id: 2, text: "How do you decide which marketing channels to prioritize for a new product launch?" },
        { id: 3, text: "Tell me about a time a campaign failed to meet its goals. What did you learn?" },
        { id: 4, text: "How do you stay updated with the latest digital marketing trends?" }
    ],
    "Data Analyst": [
        { id: 1, text: "Describe a time you found a significant anomaly in a dataset. How did you investigate it?" },
        { id: 2, text: "How do you ensure data accuracy and integrity in your reports?" },
        { id: 3, text: "Tell me about a complex data visualization you created to communicate insights." },
        { id: 4, text: "How do you explain statistical significance to stakeholders?" }
    ],
    "Customer Service Representative": [
        { id: 1, text: "Tell me about a time you dealt with a difficult or angry customer. How did you handle it?" },
        { id: 2, text: "Describe a situation where you went above and beyond for a customer." },
        { id: 3, text: "How do you handle a situation where you don't know the answer to a customer's question?" },
        { id: 4, text: "Tell me about a time you turned a negative customer experience into a positive one." }
    ],
    "Sales Representative": [
        { id: 1, text: "Describe a time you had to overcome a major objection from a prospect." },
        { id: 2, text: "Tell me about your most challenging sale. What made it difficult and how did you close it?" },
        { id: 3, text: "How do you handle rejection in your daily sales activities?" },
        { id: 4, text: "Describe a time you exceeded your sales quota. What was your strategy?" }
    ],
    "Human Resources Specialist": [
        { id: 1, text: "Tell me about a time you had to resolve a conflict between two employees." },
        { id: 2, text: "How do you handle confidential information in the workplace?" },
        { id: 3, text: "Describe a successful employee engagement initiative you implemented." },
        { id: 4, text: "Tell me about a time you had to deliver difficult news to an employee." }
    ],
    "UX/UI Designer": [
        { id: 1, text: "Walk me through your design process for a recent project." },
        { id: 2, text: "How do you balance user needs with business goals in your designs?" },
        { id: 3, text: "Tell me about a time you had to defend a design decision to stakeholders." },
        { id: 4, text: "Describe a time user testing revealed a major flaw in your design. How did you fix it?" }
    ],
    "QA Engineer": [
        { id: 1, text: "Describe a time you missed a bug that made it to production. How did you handle it?" },
        { id: 2, text: "How do you prioritize which test cases to automate?" },
        { id: 3, text: "Tell me about a complex bug you found that was difficult to reproduce." },
        { id: 4, text: "How do you handle tight deadlines when testing is squeezed at the end of a sprint?" }
    ],
    "Custom Job Description": [
        { id: 1, text: "Tell me about a project you are most proud of and your contribution to it." },
        { id: 2, text: "Describe a time you faced a significant challenge at work and how you overcame it." },
        { id: 3, text: "How do you prioritize your tasks when you have multiple deadlines?" },
        { id: 4, text: "Tell me about a time you worked effectively as part of a team." }
    ]
};
